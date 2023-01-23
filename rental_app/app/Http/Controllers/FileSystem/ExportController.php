<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileSystem;

use App\Module\Rate\Service\RateCalculatingServiceInterface;
use App\Http\Requests\ReportLoadCarsRequest;
use App\Module\Report\Load\Cars\Handler;
use App\Module\Report\Load\Cars\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTimeImmutable;
use DateTime;

final class ExportController extends Controller
{

    public function __construct(
        private Handler $handler,
        private RateCalculatingServiceInterface $rateService,
    ) {}

    private $delimetr = [
        'csv' => [
            'row_l' => "",
            'row_r' => "\n",
            'cell_l'=> "",
            'cell_r'=> ",",
        ],
        'html'=> [
            'row_l' => "<tr>",
            'row_r' => "</tr>",
            'cell_l'=> "<td>",
            'cell_r'=> "</td>",
        ],
    ];
    public function csv($start = null,$end = null)
    {

        $rentalLogs = $this->getRentalLogs($start, $end);
        $content = $this->generator('csv', $rentalLogs);
        return response()->streamDownload(function () use($content){
            echo $content;
        },"rentals_log_file$start-$end.csv");
    }

    public function html($start = null,$end = null)
    {
        $content = '';
        $content .= '<html><style> table, tr,td { border: 1px solid black; padding: 10px; }</style><table>';
        $content .= $this->generator('html', $this->getRentalLogs($start,$end));
        $content .= '</table></html>';
        return response()->streamDownload(function () use($content){
            echo $content;
        },"rentals_log_file$start-$end.html");
    }

    public function load(ReportLoadCarsRequest $request, string $start, string $end)
    {
        $from = new DateTime($start);
        $to   = new DateTime($end);

        $mounths = array();

        if((int)date_diff($from,$to)->format('%M%') >= 1){
            $period = new \DatePeriod($from, new \DateInterval('P1M'), $to);
            $mounths = array_map(
                function($item){
                    return [
                        'y' => $item->format('Y'),
                        'm' => $item->format('m')];
                },
                iterator_to_array($period)
            );
        }

        $firstDayOfStartMounth = new DateTime("first day of $start");
        $lastDayOfEndMounth = new DateTime("last day of $end");

        if ($from != $firstDayOfStartMounth) {
            array_shift($mounths);
        }

        if ($to != $lastDayOfEndMounth) {
            array_pop($mounths);
        }

        $content = '';
        $content .= '<html><style> table, tr,td { border: 1px solid black; padding: 10px; }</style><table>';

        foreach ($mounths as $mounth) {
            $date = (new DateTime('01-' . $mounth["m"] . '-' . $mounth["y"]))->format("F Y");
            $content .= '<tr><td colspan=2> <strong>Report of ' . $date . ' </strong></td></tr>';
            $content .= '<tr><td>NumberPlate</td><td>Load</td></tr>';
            $result = $this->handler->handle(Input::make($mounth['y'], $mounth['m']));
            $report = $result->getAll();
            foreach ($report['list'] as $item) {
                $content .= "<tr>" . $this->rowGen('html', $item) . "</tr>";
            }
            $content .= '<tr><td><strong>Total Load</strong></td><td>'. $report['total_load'] .'</td></tr>';
            
        }

        $content .= '</table></html>';

        return response()->streamDownload(function () use($content){
            echo $content;
        },"load_$start-$end.html");
    }

    public function getRentalLogs($start, $end)
    {
        $is_start = !is_null($start);
        $is_end = !is_null($end);

        $raw = '';

        $raw .= $is_start ? "daterange(r.rental_start, r.rental_end) @> ('$start')::date" : 'true';
        $raw .= $is_end ? " or daterange(r.rental_start, r.rental_end) @> ('$end')::date" : " and true";
        $raw .= ' or (';
        $raw .= $is_start ? "r.rental_start >= '$start'" : " true";
        $raw .= $is_end ? "and r.rental_end <= '$end')" : " and true)";

        $query = DB::table('rentals', 'r')->leftJoin('cars','cars.id','=','r.car_id')
            ->select(
                "cars.number_plate as Numberplate",
                "r.rental_start as Start",
                "r.rental_end as End",
                "r.start_salary as Salary"
            )
            ->whereRaw($raw);

        return $query->get();
    }

    public function generator(string $type, mixed $data): string
    {
        $content = '';
        $delimetr = $this->delimetr[$type];
        if (!empty($data[0])) {
            $content .= $delimetr['row_l'] 
            . $this->rowGen($type, array_keys((array)$data[0])) 
            . $delimetr['row_r'];
        }
        foreach ($data as $row) {
            $content .= $delimetr['row_l'] . $this->rowGen($type, $row) . $delimetr['row_r'];
        }
        return $content;
    }

    public function rowGen(string $type, mixed $row): string
    {
        $content = '';
        $delimetr = $this->delimetr[$type];

        foreach ($row as $key => $cell) {
            if ($key != 'Salary'){
                $content .= $delimetr['cell_l'] . $cell .  $delimetr['cell_r'];
            }
            else {
                $rate = $this->rateService->calculate(
                    date_diff(
                        new DateTimeImmutable($row->End),
                        new DateTimeImmutable($row->Start),
                    )->days + 1,
                    $row->Salary,
                    );

                $content .= $delimetr['cell_l'];
                $content .= number_format((float) round($rate / 100, 2), 2, '.', '');
                $content .= $delimetr['cell_r'];
            }
        }

        if ($type == 'csv') {
            $content = substr($content, 0, strlen($content) - 1);
        }

        return $content;
    }
}

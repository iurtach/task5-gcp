<?php
require 'vendor/autoload.php';
/*
use Google\Api\Metric;
use Google\Api\MonitoredResource;
use Google\Cloud\Monitoring\V3\MetricServiceClient;
use Google\Cloud\Monitoring\V3\Point;
use Google\Cloud\Monitoring\V3\TimeInterval;
use Google\Cloud\Monitoring\V3\TimeSeries;
use Google\Cloud\Monitoring\V3\TypedValue;
use Google\Protobuf\Timestamp;



$projectId = 'prod-401919';

function write_timeseries($projectId)
{
    $metrics = new MetricServiceClient([
        'projectId' => $projectId,
    ]);

    $projectName = $metrics->projectName($projectId);

    $endTime = new Timestamp();
    $endTime->setSeconds(time());
    $interval = new TimeInterval();
    $interval->setEndTime($endTime);

    $value = new TypedValue();
    $value->setDoubleValue(123.45);

    $point = new Point();
    $point->setValue($value);
    $point->setInterval($interval);
    $points = [$point];

    $metric = new Metric();
    $metric->setType('custom.googleapis.com/app_engine/disk_utilization');
    $labels = ['store_id' => 'Pittsburg'];
    $metric->setLabels($labels); 

    $resource = new MonitoredResource();
    $resource->setType('gae_instance');
    $labels = ['project_id' => $projectId];
    $resource->setLabels($labels);

    $timeSeries = new TimeSeries();
    $timeSeries->setMetric($metric);
    $timeSeries->setResource($resource);
    $timeSeries->setPoints($points);

    $result = $metrics->createTimeSeries(
        $projectName,
        [$timeSeries]);

    printf('Done writing time series data.' . PHP_EOL);
}

*/


use Google\Cloud\Monitoring\V3\MetricServiceClient;
use Google\Cloud\Monitoring\V3\TimeInterval;
use Google\Cloud\Monitoring\V3\ListTimeSeriesRequest_TimeSeriesView;
use Google\Protobuf\Timestamp;

$projectId = 'prod-401919';

$metricServiceClient = new MetricServiceClient();

$projectName = $metricServiceClient->projectName($projectId);
$filter = 'metric.type="custom.googleapis.com/app_engine/disk_utilization" AND resource.type="gae_instance"';
$interval = new TimeInterval();
$now = new Timestamp();
$now->setSeconds(time());
$interval->setEndTime($now);

$hoursAgo = new Timestamp();
$hoursAgo->setSeconds(time() - 3600);
$interval->setStartTime($hoursAgo);

$view = ListTimeSeriesRequest_TimeSeriesView::FULL;

$response = $metricServiceClient->listTimeSeries(
    $projectName,
    $filter,
    $interval,
    $view
);

foreach ($response->iterateAllElements() as $timeSeries) {
    print($timeSeries->getMetric()->getType() . "\n");
}

?>


<?php
use Alfred\Workflows\Workflow;

require 'vendor/autoload.php';
$workflow = new Workflow();

$arg = $argv[1];

if (\MongoId::isValid($arg)) {
    // Valid MongoID convert to date
    $result = convertMongoIDToDateTime($arg);;
    $workflow->result()
                ->uid($arg)
                ->title($result)
                ->subtitle('Press Ctrl-C to copy')
                ->type('default')
                ->valid(true)
                ->arg($result)
                ->text('copy', $result);
} else {
    // Probably a date
    $result1 = dateToObjectId($arg);
    $result2 = "ObjectId('" . dateToObjectId($arg) . "')";
    $workflow->result()
                ->uid($result1)
                ->title($result1)
                ->subtitle('Press Ctrl-C to copy')
                ->type('default')
                ->valid(true)
                ->arg($result1)
                ->text('copy', $result1);

    $workflow->result()
                ->uid($result2)
                ->title($result2)
                ->subtitle('Press Ctrl-C to copy')
                ->type('default')
                ->valid(true)
                ->arg($result2)
                ->text('copy', $result2);
}

echo $workflow->output();


function dateToObjectId($str)
{
    try {
        $date = new \DateTime($str);
    } catch (\Exception $e) {
        return "Invalid Date '$str'";
    }
    $mongoId = new \MongoId(strval(dechex($date->getTimestamp())) . '0000000000000000');
    return (string) $mongoId;
}

function convertMongoIDToDateTime($id)
{
    $mongoId = new \MongoId($id);
    $date = new \DateTime();
    $date->setTimestamp($mongoId->getTimestamp());
    return $date->format('c');
}

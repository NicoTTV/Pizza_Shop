<?php
namespace pizzashop\commande\app\messages;

require __DIR__ . '/../../../vendor/autoload.php';

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
try {
    //$connection = new AMQPStreamConnection(getenv('rabbit_host'), getenv('rabbit_port'), getenv('rabbit_user'), getenv('rabbit_password'));
    $connection = new AMQPStreamConnection('localhost', 4500, 'commande', 'commande');
} catch (\Exception $e) {
    echo "Impossible de se connecter à RabbitMQ: " . $e->getMessage() . "\n";
    exit(1);
}
$channel = $connection->channel();

$callback = function (AMQPMessage $msg) {
    $msg_body = json_decode($msg->body, true);
    print " [x] Message Received :\n";
    var_dump($msg_body);
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};
$channel->basic_consume('nouvelles_commandes', '', false, false, false, false, $callback);
try{
    $channel->consume();
} catch (Exception $e){
    echo "Impossible de consommer le message: " . $e->getMessage() . "\n";
    exit(1);
}
$channel->close();
try {
    $connection->close();
} catch (\Exception $e) {
    echo "Impossible de fermer la connexion à RabbitMQ: " . $e->getMessage() . "\n";
    exit(1);
}
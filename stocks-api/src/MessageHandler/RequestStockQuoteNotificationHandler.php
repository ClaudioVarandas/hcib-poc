<?php

namespace App\MessageHandler;

use App\Message\RequestStockQuoteNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RequestStockQuoteNotificationHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer
    ) {
    }

    public function __invoke(RequestStockQuoteNotification $message): void
    {

        $emailAddress = $message->getEmail();
        $content = $message->getContent();

        // Compose email
        $email = (new Email())
            ->from('cvarandas+hcbi@gmail.com')
            ->to($emailAddress)
            ->subject('HCIB - You stock quote query result.')
            ->text(json_encode($content,JSON_PRETTY_PRINT));
            //->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            // Send email
            $this->mailer->send($email);
            // Log
            $this->logger->info("RequestStockQuoteNotificationHandler - Email notification sent to : $emailAddress ");
        } catch (TransportExceptionInterface $e) {
            $errorMessage = $e->getMessage();

            $this->logger->error(
                "RequestStockQuoteNotificationHandler - Unable to send email notification to : $emailAddress .",
                ['error_message' => $errorMessage, 'exception' => $e->getTraceAsString()]
            );
        }
    }
}
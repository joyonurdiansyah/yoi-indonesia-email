<?php

namespace App\Http\Controllers;

use App\Traits\MailService;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\CreateSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmail;

class SendMailController extends Controller
{
    public function sendMail(Request $request)
    {
        $result = $this->registrationNewUser($request['name'], $request['email'], $request['password'], $request['activation_code']);

        printf($result);

        return 'success';
    }

    /**
     * Send email service integrated with SendInBlue
     *
     * @throws ApiException
     */
    public function sendMailSendInBlue($subject, $name, $address, $mail_content): CreateSmtpEmail
    {
        // Setup credentials with SendInBlue
        $apiKey = env('SENDINBLUE_API_KEY');
        $credentials = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $apiInstance = new TransactionalEmailsApi(new Client(), $credentials);

        // Setup request email that will be sent to SendInBlue
        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $subject,
            'sender' => ['name' => 'YOI Indonesia', 'email' => 'admin@yoiindonesia.com'],
            'to' => [[ 'name' => $name, 'email' => $address]],
            'htmlContent' => $mail_content
        ]);

        // Do sending transaction email with SendInBlue
        return $apiInstance->sendTransacEmail($sendSmtpEmail);
    }

    /**
     * Registration email service for new account
     *
     * @throws ApiException
     */
    public function registrationNewUser($name, $email, $password, $activation_code): CreateSmtpEmail
    {
        $subject = 'Registrasi Akun Baru';

        $data = array(
            'name'          => $name,
            'email'         => $email,
            'password'      => $password,
            'activation_code' => $activation_code
        );

        $mail_content = view('mail')->with($data)->render();

        return $this->sendMailSendInBlue($subject, $name, $email, $mail_content);
    }
}
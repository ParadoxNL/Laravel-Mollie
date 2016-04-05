<?php

namespace ParadoxNL\Mollie;

use Illuminate\Http\Request;
use Mollie_API_Client;
use Mollie_API_Object_Method;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Mollie
{
    /**
     * Mollie API Client
     *
     * @var Mollie_API_Client
     */
    protected $mollie;

    /**
     * Mollie constructor.
     *
     * @param Mollie_API_Client $client
     */
    public function __construct(Mollie_API_Client $client)
    {
        $this->mollie = $client;
        $this->mollie->setApiKey(config('mollie.api_key'));
    }

    /**
     * Create a new payment
     *
     * @param $id
     * @param float|int $amount
     * @param string $description
     * @param string $type
     * @param $parameters
     * @param array $extra
     * @return \Mollie_API_Object_Payment
     */
    public function createPayment($id, $amount = 0, $description, $type = Mollie_API_Object_Method::IDEAL, $parameters = [], $extra = [])
    {
        try {
            return $this->mollie->payments->create(array_merge([
                "amount" => $amount,
                "description" => $description,
                "redirectUrl" => config('mollie.redirect_url'),
                "webhookUrl" => config('mollie.webhook_url'),
                "method" => $type,
                "metadata" => array_merge([ 
                    "order_id" => $id,
                ], $extra)
            ], $parameters));
        } catch (\Mollie_API_Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 500);
        }
    }

    /**
     * Webhook callback to check whether a payment is successful
     *
     * @param Request $request
     * @throws \HttpException
     * @throws \Mollie_API_Exception
     * @return bool
     */
    public function isPaid(Request $request)
    {
        if ($request->has('testByMollie')) {
            die('OK');
        }
        try {
            return $this->mollie->payments->get($request->input('id'))->isPaid();
        } catch (\Mollie_API_Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * Check whether an order is still in process
     *
     * @param Request $request
     * @return bool
     */
    public function isOpen(Request $request)
    {
        try {
            return $this->mollie->payments->get($request->input('id'))->isOpen();
        } catch (\Mollie_API_Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * Refund a payment based on id
     *
     * @param null $id
     * @param null $amount
     * @throws \HttpException
     * @return \Mollie_API_Object_Payment_Refund
     */
    public function refund($id, $amount = null)
    {
        try {
            $payment = $this->mollie->payments->get($id);

            if ($payment->canBeRefunded()) {
                return $this->mollie->payments->refund($payment, ($amount) ? $amount : $payment->amount);
            } else {
                throw new BadRequestHttpException('Unable to process refund');
            }
        } catch (\Mollie_API_Exception $e) {
            throw new \HttpException($e->getMessage(), 500);
        }
    }

    /**
     * List all transactions
     *
     * @throws BadRequestHttpException
     * @return \Mollie_API_Object_List|\Mollie_API_Object_Payment[]
     */
    public function history()
    {
        try {
            return $this->mollie->payments->all(config('mollie.offset', 0), config('mollie.limit', 25));
        } catch (\Mollie_API_Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * Get the mollie instance
     *
     * @return Mollie_API_Client
     */
    public function getClient()
    {
        return $this->mollie;
    }
}

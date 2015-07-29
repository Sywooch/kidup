<?php

$payin = array(
    array( // row #0
        'id' => 1,
        'data' => NULL,
        'type' => NULL,
        'user_id' => 3,
        'status' => 'init',
        'currency_id' => 1,
        'created_at' => 1433936911,
        'updated_at' => 1433936911,
        'nonce' => 'fake-valid-nonce',
        'braintree_backup' => NULL,
        'amount' => 538,
        'invoice_id' => NULL,
    ),
    array( // row #1
        'id' => 2,
        'data' => NULL,
        'type' => NULL,
        'user_id' => 3,
        'status' => 'init',
        'currency_id' => 1,
        'created_at' => 1433936946,
        'updated_at' => 1433936946,
        'nonce' => 'fake-valid-nonce',
        'braintree_backup' => NULL,
        'amount' => 3006,
        'invoice_id' => NULL,
    ),
    array( // row #2
        'id' => 3,
        'data' => NULL,
        'type' => NULL,
        'user_id' => 3,
        'status' => 'status_authorized',
        'currency_id' => 1,
        'created_at' => 1433936975,
        'updated_at' => 1433936984,
        'nonce' => '803aca4f-fd31-4f40-a655-1c91d383679b',
        'braintree_backup' => '{"id":"2ybyhj","status":"authorized","type":"sale","currencyIsoCode":"DKK","amount":"299.00","merchantAccountId":"dkk-merchant","orderId":null,"createdAt":{"date":"2015-06-10 11:49:41.000000","timezone_type":3,"timezone":"UTC"},"updatedAt":{"date":"2015-06-10 11:49:41.000000","timezone_type":3,"timezone":"UTC"},"customer":{"id":null,"firstName":null,"lastName":null,"company":null,"email":null,"website":null,"phone":null,"fax":null},"billing":{"id":null,"firstName":null,"lastName":null,"company":null,"streetAddress":null,"extendedAddress":null,"locality":null,"region":null,"postalCode":null,"countryName":null,"countryCodeAlpha2":null,"countryCodeAlpha3":null,"countryCodeNumeric":null},"refundId":null,"refundIds":[],"refundedTransactionId":null,"settlementBatchId":null,"shipping":{"id":null,"firstName":null,"lastName":null,"company":null,"streetAddress":null,"extendedAddress":null,"locality":null,"region":null,"postalCode":null,"countryName":null,"countryCodeAlpha2":null,"countryCodeAlpha3":null,"countryCodeNumeric":null},"customFields":"","avsErrorResponseCode":null,"avsPostalCodeResponseCode":"I","avsStreetAddressResponseCode":"I","cvvResponseCode":"I","gatewayRejectionReason":null,"processorAuthorizationCode":"Q3F0DJ","processorResponseCode":"1000","processorResponseText":"Approved","additionalProcessorResponse":null,"voiceReferralNumber":null,"purchaseOrderNumber":null,"taxAmount":null,"taxExempt":false,"creditCard":{"token":null,"bin":"411111","last4":"1111","cardType":"Visa","expirationMonth":"10","expirationYear":"2019","customerLocation":"US","cardholderName":null,"imageUrl":"https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox","uniqueNumberIdentifier":null,"prepaid":"Unknown","healthcare":"Unknown","debit":"Unknown","durbinRegulated":"Unknown","commercial":"Unknown","payroll":"Unknown","issuingBank":"Unknown","countryOfIssuance":"Unknown","productId":"Unknown","venmoSdk":false},"statusHistory":[{"_attributes":{"timestamp":{"date":"2015-06-10 11:49:41.000000","timezone_type":3,"timezone":"UTC"},"status":"authorized","amount":"299.00","user":"esquire900","transactionSource":"api"}}],"planId":null,"subscriptionId":null,"subscription":{"billingPeriodEndDate":null,"billingPeriodStartDate":null},"addOns":[],"discounts":[],"descriptor":{"_attributes":{"name":null,"phone":null,"url":null}},"recurring":false,"channel":null,"serviceFeeAmount":null,"escrowStatus":null,"disbursementDetails":{},"disputes":[],"paymentInstrumentType":"credit_card","processorSettlementResponseCode":"","processorSettlementResponseText":"","threeDSecureInfo":null,"creditCardDetails":{},"customerDetails":{"_attributes":{"id":null,"firstName":null,"lastName":null,"company":null,"email":null,"website":null,"phone":null,"fax":null}},"billingDetails":{},"shippingDetails":{},"subscriptionDetails":{"_attributes":{"billingPeriodEndDate":null,"billingPeriodStartDate":null}}}',
        'amount' => 299,
        'invoice_id' => NULL,
    ),
);

return $payin;
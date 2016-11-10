<?php

$response_code_arr["A01"] = "APPROVED Transaction approved and completed"; 

$response_code_arr["A03"] = "PARTIAL AUTHORIZATION Transaction approved for a partial authorization (CC only) not available"; 

$response_code_arr["U01"] = "MERCH AUTH REVOKED Merchant not allowed to access customer account (EFT only) not available"; 

$response_code_arr["U02"] = "ACCOUNT NOT APPROVED Customer account is in the Forte 'known bad' account list (EFT only) send eCheck sale transaction with: routing number : 021000021 account number: 987654321 Â©2015 Forte Payment Systems Proprietary and Confidential 42
Code Description Comments Test Parameters U02 TRN NOT APPROVED Routing number passes checksum test but not valid for ACH send eCheck sale transaction with routing number: 064000101 and any account number"; 

$response_code_arr["U03"] = "DAILY TRANS LIMIT Merchant daily limit exceeded (EFT only) not available"; 

$response_code_arr["U04"] = "MONTHLY TRANS LIMIT Merchant monthly limit exceeded (EFT only) not available"; 

$response_code_arr["U05"] = "AVS FAILURE ZIPCODE AVS state/zipcode check failed.  Please check and re-enter state and/or zip code"; 

$response_code_arr["U06"] = "AVS FAILURE AREACODE AVS state/area code check failed - please enter a matching state and area code"; 

$response_code_arr["U07"] = "AVS FAILURE EMAIL AVS anonymous email check failed"; 

$response_code_arr["U10"] = "DUPLICATE TRANSACTION Transaction has the same attributes as another transaction within the time set by the merchant send the same transaction twice within five minutes"; 

$response_code_arr["U11"] = "RECUR TRANS NOT FOUND Transaction types 40-42 only not available"; 

$response_code_arr["U12"] = "UPDATE NOT ALLOWED Original transaction not voidable or capture-able send void transaction for declined transaction"; 

$response_code_arr["U13"] = "ORIG TRANS NOT FOUND Transaction to be voided or captured was not found send void transaction for trace number 00000000-0000-0000-0000-000000000000"; 

$response_code_arr["U14"] = "BAD TYPE FOR ORIG TRANS Void/capture and original transaction types do not agree (CC/EFT) send a void cc transaction for an eCheck transaction"; 

$response_code_arr["U15"] = "ALREADY VOIDED ALREADY CAPTURED Transaction was previously voided or captured void the same transaction twice"; 

$response_code_arr["U18"] = "UPDATE FAILED Void or Capture failed"; 

$response_code_arr["U19"] = "INVALID TRN Account ABA number if invalid"; 

$response_code_arr["U20"] = "INVALID CREDIT CARD NUMBER Credit card number is invalid"; 

$response_code_arr["U21"] = "BAD START DATE Date is malformed"; 

$response_code_arr["U22"] = "SWIPE DATA FAILURE Swipe data is malformed"; 

$response_code_arr["U23"] = "INVALID EXPIRATION DATE Expiration date is expired, please check and re-enter"; 

$response_code_arr["U25"] = "INVALID AMOUNT Negative amount"; 

$response_code_arr["U26"] = "INVALID DATA** Invalid data present in transaction"; 

$response_code_arr["U27"] = "CONV FEE NOT ALLOWED Merchant sent a convenience fee but is not configured to accept one For merchants not configured to accept a convenience fee, send a transaction with a convenience fee in pg_convenience_fee"; 

$response_code_arr["U28"] = "CONV FEE INCORRECT Merchant configured for convenience fee but did not send one For merchants configured to accept a convenience fee, send a transaction with an incorrect convenience fee in pg_convenience_fee"; 

$response_code_arr["U29"] = "CONV FEE DECLINED Convenience fee transaction failed â€“ SplitCharge model only N/A"; 

$response_code_arr["U30"] = "PRINCIPAL DECLINED Principal transaction failed â€“ SplitCharge model only N/A"; 

$response_code_arr["U51"] = "MERCHANT STATUS Merchant is not 'live' send a transaction for a non-Live merchant id"; 

$response_code_arr["U52"] = "TYPE NOT ALLOWED Merchant not approved for transaction type.  Please re-enter a valid, personal MasterCard, VISA, AMEX, or Discover number (no pre-paid cards)"; 

$response_code_arr["U53"] = "PER TRANS LIMIT Transaction amount exceeds merchant's per transaction limit (EFTs only) send a transaction that exceeds the mechants eCheck limit(s)"; 

$response_code_arr["U54"] = "INVALID MERCHANT CONFIG Merchant's configuration requires updating â€“ call customer support"; 

$response_code_arr["U80"] = "PREAUTH DECLINE Transaction was declined due to preauthorization (Forte Verify) "; 

$response_code_arr["U81"] = "PREAUTH TIMEOUT Preauthorizer not responding (Verify Only transactions only)"; 

$response_code_arr["U82"] = "PREAUTH ERROR Preauthorizer error (Verify Only transactions only)"; 

$response_code_arr["U83"] = "AUTH DECLINE* Payment error, your transaction could not be processed. Please review your address and credit card information and try again.  Or call us at 800-994-1846 and weâ€™ll be happy to place the order for you!
(Error Code: U83 - General decline of the card)"; 

$response_code_arr["U84"] = "AUTH TIMEOUT Authorizer not responding, transaction timed out"; 

$response_code_arr["U85"] = "AUTH ERROR Authorizer error"; 

$response_code_arr["U86"] = "AVS FAILURE AUTH Authorizer AVS check failed.  Please check address information and try again."; 

$response_code_arr["U87"] = "AUTH BUSY Authorizing Vendor busy, may be resubmitted"; 

$response_code_arr["U88"] = "PREAUTH BUSY Verification vendor busy, may be resubmitted"; 

$response_code_arr["U89"] = "AUTH UNAVAIL Transaction failed.  Vendor service unavailable"; 

$response_code_arr["U90"] = "PREAUTH UNAVAIL Transaction failed.  Verification service unavailable"; 

$response_code_arr["POS"] = "pg_3d_secure_result=POS [response message field] 3DS credit card verification service for enrolled merchants Send a credit card sale transaction with an ecom_3d_secure_data value starting with '7'"; 

$response_code_arr["UNK"] = "pg_3d_secure_result=UNK [response message field] 3DS credit card verification service for enrolled merchants Send a credit card sale transaction with an ecom_3d_secure_data value starting with '8'"; 

$response_code_arr["NEG"] = "pg_3d_secure_result=NEG [response message field] 3DS credit card verification service for enrolled merchants Send a credit card sale transaction with an ecom_3d_secure_data value starting with '9' Table A.1 - Approved and Declined Responses Â©2015 Forte Payment Systems Proprietary and Confidential 45 
*pg_response_description will contain the text of the vendor's response **pg_response_description will contain a more specific message Formatting Error Responses These are the codes returned when formatting errors are found. The response description field will actually list all offending fields in the message (to the 80 character limit). The description field will be formatted as: <code>:<fieldname>[,<code>:<fieldname> ...] The pg_response_code will contain the first error type encountered. All formatting errors begin with 'F'. Code Description Comments"; 

$response_code_arr["F01"] = "MANDATORY FIELD MISSING Required field is missing"; 

$response_code_arr["F03"] = "INVALID FIELD NAME Name is not recognized"; 

$response_code_arr["F04"] = "INVALID FIELD VALUE Value is not allowed"; 

$response_code_arr["F05"] = "DUPLICATE FIELD Field is repeated in message"; 

$response_code_arr["F07"] = "CONFLICTING FIELD Fields cannot both be present Table A.2- Formatting Error Codes Fatal Exceptions Responses These exceptions will stop the processing of a well-formed message due to security or other considerations. All fatal exceptions begin with an 'E.' Code Description Comments"; 

$response_code_arr["E10"] = "INVALID MERCH OR PASSWD Merchant ID or processing password is incorrect"; 

$response_code_arr["E20"] = "MERCHANT TIMEOUT Transaction message not received (I/O flush required?)"; 

$response_code_arr["E55"] = "INVALID TOKEN Specified token was invalid, could not be located, or may have been deleted Client Token Transactions For client-token transactions where neither payment fields nor a payment token were specified, the client record does not have a default payment method matching the transaction type Payment Token Transactions For payment token transactions where no client token is specified, the payment token must clientless Â©2015 Forte Payment Systems Proprietary and Confidential 46 
 Both Client and Payment Tokens Present For transactions with client and payment tokens, the specified payment token is not associated with the client or is clientless"; 

$response_code_arr["E90"] = "BAD MERCH IP ADDR Originating IP not on merchant's approved IP list"; 

$response_code_arr["E99"] = "INTERNAL ERROR An unspecified error has occurred";	
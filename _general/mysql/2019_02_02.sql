/*Not Finished 2019-09-02*/
UPDATE
    rent_payment
SET
    PNTExtraIQD=PNTTotalIQD
WHERE
	PNTDeleted=0 AND
    PNTTotalIQD>0



ALTER TABLE rent_payment ADD PNTInvoiceId INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER PNTID;


UPDATE
    rent_payment
SET
    PNTInvoiceId=PNTID
WHERE
	PNTDeleted=0

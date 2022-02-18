UPDATE
    capital
SET
	CPTTransactionType=6
WHERE
    CPTDeleted=0 AND
    CPTFORID is null AND
    CPTTransactionType=5
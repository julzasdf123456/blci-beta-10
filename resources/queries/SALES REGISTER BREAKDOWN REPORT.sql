SELECT Town,
	(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS ConsumerCount,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS TotalKwhused,
	(SELECT SUM(CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS KwhSold,
	(SELECT SUM(CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS TotalAmount,
	(SELECT SUM(CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS Missionary,
	(SELECT SUM(CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS Environmental,
	(SELECT SUM(CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS NPC,
	(SELECT SUM(CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS StrandedCC,
	(SELECT SUM(CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS Redci,
	(SELECT SUM(CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS FITAll,
	(SELECT SUM(CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS RPT,
	(SELECT SUM(CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS GenVat,
	(SELECT SUM(CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS TransVat,
	(SELECT SUM(CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS SysLossVat,
	(SELECT SUM(CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01') AS DistVat,
	(SELECT SUM(CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy,
	(SELECT SUM(CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=t.id AND b.ServicePeriod='2022-01-01' AND CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc
FROM CRM_Towns t
ORDER BY t.id


SELECT * FROM Billing_Bills ORDER BY MissionaryElectrificationCharge

--UPDATE Billing_Bills SET SeniorCitizenSubsidy='0' WHERE SeniorCitizenSubsidy='#VALUE!'



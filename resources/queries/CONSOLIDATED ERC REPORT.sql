SELECT TOP 1
	(SELECT SUM(CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ServicePeriod='2022-01-01' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS GenerationSystem,
	(SELECT SUM(CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ServicePeriod='2022-01-01' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS')) AS LowVoltage,
	(SELECT SUM(CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ServicePeriod='2022-01-01' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage,
	(SELECT SUM(CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ServicePeriod='2022-01-01' AND ConsumerType NOT IN ('STREET LIGHTS')) AS TotalAmount
FROM Billing_Rates

SELECT GenerationSystemCharge FROM Billing_Bills ORDER BY GenerationSystemCharge

-- UPDATE Billing_Bills SET GenerationSystemCharge='0' WHERE GenerationSystemCharge='#VALUE!'
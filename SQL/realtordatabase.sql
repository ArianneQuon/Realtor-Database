
CREATE TABLE Maintenance(
empNo INTEGER,
mName CHAR(20),
Company CHAR(20),
PRIMARY KEY (empNo)
);

grant select on Maintenance to public;

CREATE TABLE Plumber(
    empNo INTEGER,
    yearsOfExperience INTEGER,
    PRIMARY KEY (empNo)
);
grant select on Plumber to public;


CREATE TABLE Cleaner(
    empNo INTEGER,
    Specialty CHAR(20),
    PRIMARY KEY (empNo)
);
grant select on Cleaner to public;


CREATE TABLE Gardener(
    empNo INTEGER,
    tool CHAR(20),
    PRIMARY KEY (empNo)
);

grant select on Gardener to public;

CREATE TABLE Bank(
    branchID INTEGER,
    phoneNumber INTEGER,
    bankAddress CHAR(20),
    brCompany CHAR(60),
    PRIMARY KEY (branchId)
);

grant select on Bank to public;


CREATE TABLE InsurancePurchased(
    insuranceID INTEGER,
    insuranceType CHAR(30),
    PRIMARY KEY (insuranceID)
);

grant select on InsurancePurchased to public;

CREATE TABLE InsurancePackage(
    insuranceType CHAR(30),
    insurancePrice INTEGER
);

grant select on InsurancePackage to public;


CREATE TABLE Buyer(
    buyerID INTEGER,
    bName CHAR(20),
    contactInfo CHAR(20),
    incomeLevel INTEGER,
    contractNum INTEGER,
    PRIMARY KEY (buyerID)
);

grant select on Buyer to public;


CREATE TABLE BudgetAmount(
    incomeLevel INTEGER,
    Budget INTEGER
);

grant select on BudgetAmount to public;

CREATE TABLE PropertyPrice( 
    sqFt INTEGER,
    sellPrice INTEGER,
    PRIMARY KEY (sqFt)
); 

grant select on PropertyPrice to public;

CREATE TABLE Realtor (
	realtorID INTEGER,
	rName CHAR(20),
	rCompany CHAR(50), 
	PRIMARY KEY (realtorID)
);

grant select on Realtor to public;


CREATE TABLE Seller( 
    sellerID INTEGER,
    branchID INTEGER,
    sName CHAR(30),
    transactionNumber INTEGER,
    reservationPrice INTEGER,   
    PRIMARY KEY (sellerID),
    FOREIGN KEY (branchID) REFERENCES Bank(branchID)
        ON DELETE SET NULL
);

grant select on Seller to public;


CREATE TABLE Hire (
	sellerID INTEGER,
	empNo INTEGER,
    Invoice INTEGER,
	PRIMARY KEY (sellerID, empNo),
	FOREIGN KEY (sellerID) REFERENCES Seller(sellerID)
        ON DELETE CASCADE,
	FOREIGN KEY (empNo) REFERENCES Maintenance(empNo)
		ON DELETE CASCADE
);

grant select on Hire to public;


CREATE TABLE Property( 
    propertyID INTEGER,
    propertyAddress CHAR(20),
    sqFt INTEGER,
    contractNum INTEGER,
    buyPrice INTEGER,
    realtorID INTEGER,
    buyerID INTEGER,
    sellerID INTEGER,
    PRIMARY KEY (propertyID),
    FOREIGN KEY (sqft) REFERENCES PropertyPrice(sqft)
        ON DELETE SET NULL,
    FOREIGN KEY (realtorID) REFERENCES Realtor(realtorID)
        ON DELETE SET NULL,
    FOREIGN KEY (buyerID) REFERENCES Buyer(buyerID)
        ON DELETE SET NULL,
    FOREIGN KEY (sellerID) REFERENCES Seller(sellerID)
        ON DELETE CASCADE
);

grant select on Property to public;

CREATE TABLE OfficeBuilding (
	propertyID INTEGER,
	numFloors INTEGER,
	PRIMARY KEY  (propertyID)
);
grant select on OfficeBuilding to public;


CREATE TABLE House (
	propertyID INTEGER,
	numBed INTEGER,
	numBath INTEGER,
	PRIMARY KEY  (propertyID)
);

grant select on House to public;

CREATE TABLE Apartment (
	propertyID INTEGER,
	numRooms INTEGER,
	PRIMARY KEY  (propertyID)
);

grant select on Apartment to public;

CREATE TABLE MarketingCampaign (
	realtorName CHAR(20),
    realtorID INTEGER,
    websiteAddress CHAR(100),
    PRIMARY KEY (realtorName, realtorID),
    FOREIGN KEY (realtorID) REFERENCES Realtor(realtorID)
        ON DELETE CASCADE
);
grant select on MarketingCampaign to public;

CREATE TABLE Insure (
branchID INTEGER,
    insuranceID INTEGER,
    PRIMARY KEY (branchID, insuranceID),
    FOREIGN KEY (branchID) REFERENCES Bank(branchId)
        ON DELETE CASCADE,
    FOREIGN KEY (insuranceID) REFERENCES InsurancePurchased(insuranceID)
        ON DELETE CASCADE
);

grant select on Insure to public;


CREATE TABLE Loan (
    buyerID INTEGER,
    branchID INTEGER,
    loanOfficerName CHAR(20),
    loanOfficerID INTEGER,
    loanAmt INTEGER,
    PRIMARY KEY (buyerID, branchID),
    FOREIGN KEY (branchID) REFERENCES Bank(branchID)
            ON DELETE CASCADE,
    FOREIGN KEY (buyerID) REFERENCES Buyer(buyerID)
            ON DELETE CASCADE
);

grant select on Loan to public;




INSERT INTO Maintenance (empNo, mName, Company)
VALUES (1, 'Aliaa Bhan', 'Jessicas Plumbing');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (2, 'Charlie Don', 'Jessicas Plumbing');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (3, 'Emilia Fan', 'Jessicas Plumbing');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (4, 'George Harrison', 'Jessicas Plumbing');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (5, 'Ivan Jerich', 'Jessicas Plumbing');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (6, 'Kevin Leroy', 'University Cleaners');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (7, 'Jam Kriss', 'University Cleaners');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (8, 'Liam Murray', 'University Cleaners');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (9, 'Niall ONeill', 'University Cleaners');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (10, 'Peter Quest', 'University Cleaners');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (11, 'Rachel Su', 'Garden Folks');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (12, 'Tammy Urq', 'Garden Folks');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (13, 'Vinny Am', 'Garden Folks');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (14, 'William Chow', 'Garden Folks');

INSERT INTO Maintenance (empNo, mName, Company)
VALUES (15, 'Carl Zyggs', 'Garden Folks');


INSERT INTO Plumber (empNo, yearsOfExperience)
VALUES (1, 3);

INSERT INTO Plumber (empNo, yearsOfExperience)
VALUES (2, 5);

INSERT INTO Plumber (empNo, yearsOfExperience)
VALUES (3, 7);

INSERT INTO Plumber (empNo, yearsOfExperience)
VALUES (4, 9);

INSERT INTO Plumber (empNo, yearsOfExperience)
VALUES (5, 11);


INSERT INTO Cleaner (empNo, Specialty)
VALUES (6, 'Window');

INSERT INTO Cleaner (empNo, Specialty)
VALUES (7, 'Driveway');

INSERT INTO Cleaner (empNo, Specialty)
VALUES (8, 'Bedroom');

INSERT INTO Cleaner (empNo, Specialty)
VALUES (9, 'Rooftop');

INSERT INTO Cleaner (empNo, Specialty)
VALUES (10, 'Ceiling');


INSERT INTO Gardener (empNo, Tool)
VALUES  (11, 'Wheelbarrow');

INSERT INTO Gardener (empNo, Tool)
VALUES  (12, 'Hedge Shears');

INSERT INTO Gardener (empNo, Tool)
VALUES  (13, 'Rake');

INSERT INTO Gardener (empNo, Tool)
VALUES  (14, 'Pruners');

INSERT INTO Gardener (empNo, Tool)
VALUES  (15, 'Shovel');


INSERT INTO Bank (branchID, phoneNumber, bankAddress, brCompany)
VALUES(001, 2038081045, '100 Main Mall', 'Canada Trust');

INSERT INTO Bank (branchID, phoneNumber, bankAddress, brCompany)
VALUES(002, 2039991234, '200 University', 'Canada Trust');

INSERT INTO Bank (branchID, phoneNumber, bankAddress, brCompany)
VALUES(003, 2030010010, '300 Thunderbird', 'Canada Trust');

INSERT INTO Bank (branchID, phoneNumber, bankAddress, brCompany)
VALUES(004, 3072221424, '105 Main Mall', 'CIBC');

INSERT INTO Bank (branchID, phoneNumber, bankAddress, brCompany)
VALUES(005, 3071017890, '210 University', 'CIBC');


INSERT INTO InsurancePurchased (insuranceID, insuranceType)
VALUES  
(123, 'Mortgage Insurance');

INSERT INTO InsurancePurchased (insuranceID, insuranceType)
VALUES  (124, 'Credit Insurance');

INSERT INTO InsurancePurchased (insuranceID, insuranceType)
VALUES  (125, 'Homeowner Insurance');

INSERT INTO InsurancePurchased (insuranceID, insuranceType)
VALUES  (126, 'Life Insurance');

INSERT INTO InsurancePurchased (insuranceID, insuranceType)
VALUES  (127, 'Loan Insurance');


INSERT INTO InsurancePackage (insuranceType, insurancePrice)
VALUES  
('Mortgage Insurance', 8000);

INSERT INTO InsurancePackage (insuranceType, insurancePrice)
VALUES ('Credit Insurance', 2000);

INSERT INTO InsurancePackage (insuranceType, insurancePrice)
VALUES  ('Homeowner Insurance', 2000);

INSERT INTO InsurancePackage (insuranceType, insurancePrice)
VALUES  ('Life Insurance', 10000);

INSERT INTO InsurancePackage (insuranceType, insurancePrice)
VALUES  ('Loan Insurance', 4000);


INSERT INTO Realtor (realtorID, rName, rCompany)
VALUES (1, 'Steve Choi', 'RE/SELL');

INSERT INTO Realtor (realtorID, rName, rCompany)
VALUES (2, 'Brian Brown', 'Browns Buys');

INSERT INTO Realtor (realtorID, rName, rCompany)
VALUES (3, 'Melinda Li', 'Heatwell Banker Real Estate');

INSERT INTO Realtor (realtorID, rName, rCompany)
VALUES (4, 'Sabine Miller', 'RE/SELL');

INSERT INTO Realtor (realtorID, rName, rCompany)
VALUES (5, 'Ella Morrison', 'Mountainside Sellers');




INSERT INTO OfficeBuilding (propertyID, numFloors)
VALUES  (1, 20);

INSERT INTO OfficeBuilding (propertyID, numFloors)
VALUES  (2, 13);

INSERT INTO OfficeBuilding (propertyID, numFloors)
VALUES  (3, 15);

INSERT INTO OfficeBuilding (propertyID, numFloors)
VALUES  (4, 8);

INSERT INTO OfficeBuilding (propertyID, numFloors)
VALUES  (5, 4);


INSERT INTO House (propertyID, numBed, numBath)
VALUES (6, 2, 1);

INSERT INTO House (propertyID, numBed, numBath)
VALUES (7, 3, 2);

INSERT INTO House (propertyID, numBed, numBath)
VALUES (8, 5, 5);

INSERT INTO House (propertyID, numBed, numBath)
VALUES (9, 2, 2);

INSERT INTO House (propertyID, numBed, numBath)
VALUES (10, 7, 5);


INSERT INTO Apartment (propertyID, numRooms)
VALUES 
(11, 1);

INSERT INTO Apartment (propertyID, numRooms)
VALUES(12, 2);

INSERT INTO Apartment (propertyID, numRooms)
VALUES(13, 3);

INSERT INTO Apartment (propertyID, numRooms)
VALUES(14, 6);

INSERT INTO Apartment (propertyID, numRooms)
VALUES(15, 4);

INSERT INTO Buyer(buyerID, bName, contactInfo, incomeLevel, contractNum)
VALUES(1, 'Josh Lee', 'joshlee@gmail.com', 1, 10);

INSERT INTO Buyer(buyerID, bName, contactInfo, incomeLevel, contractNum)
VALUES(2, 'Hanna Ox', 'hox123@hotmail.com', 2, 11);

INSERT INTO Buyer(buyerID, bName, contactInfo, incomeLevel, contractNum)
VALUES(3, 'Betty Swift', 'betty1122@gmail.com', 2, 12);

INSERT INTO Buyer(buyerID, bName, contactInfo, incomeLevel, contractNum)
VALUES(4, 'Kanye West', 'kwestbb@yahoo.ca', 3, 13);

INSERT INTO Buyer(buyerID, bName, contactInfo, incomeLevel, contractNum)
VALUES(5, 'Kim Kar', 'kimmy098@gmail.com', 3, 14);

INSERT INTO Seller(sellerID, sName, branchID, transactionNumber, reservationPrice)
VALUES(1, 'Joy Lee', 002, 100, 400000);

INSERT INTO Seller(sellerID, sName, branchID, transactionNumber, reservationPrice)
VALUES(2, 'Adam Chen', 004, 200, 300000);

INSERT INTO Seller(sellerID, sName, branchID, transactionNumber, reservationPrice)
VALUES(3, 'Mike Morrison', 001, 300, 200000);

INSERT INTO Seller(sellerID, sName, branchID, transactionNumber, reservationPrice)
VALUES(4, 'Frederick Upton', 005, 400, 550000);

INSERT INTO Seller(sellerID, sName, branchID, transactionNumber, reservationPrice)
VALUES(5, 'Esther Kim', 002, 500, 499000);

	

INSERT INTO MarketingCampaign (realtorName, realtorID, websiteAddress)
VALUES ('Steve Choi', 1, 'www.steve.com');
	
	INSERT INTO MarketingCampaign (realtorName, realtorID, websiteAddress)
VALUES('Brian Brown', 2, 'www.bbhouses.com');
	INSERT INTO MarketingCampaign (realtorName, realtorID, websiteAddress)
VALUES('Melinda Li', 3, 'www.lindaLi.com');
	INSERT INTO MarketingCampaign (realtorName, realtorID, websiteAddress)
VALUES('Sabine Miller', 4, 'www.sabiii.com');
	INSERT INTO MarketingCampaign (realtorName, realtorID, websiteAddress)
VALUES('Ella Morrison', 5, 'www.ellamorri.com');

/* need bank inserted*/
INSERT INTO Insure(branchID, insuranceID) 
VALUES(001, 123);

INSERT INTO Insure(branchID, insuranceID) 
VALUES(002, 124);

INSERT INTO Insure(branchID, insuranceID) 
VALUES(003, 125);

INSERT INTO Insure(branchID, insuranceID) 
VALUES(004, 126);

INSERT INTO Insure(branchID, insuranceID) 
VALUES(005, 127);

INSERT INTO BudgetAmount(incomeLevel, Budget)
VALUES(1, 100000);

INSERT INTO BudgetAmount(incomeLevel, Budget)
VALUES(2, 250000);

INSERT INTO BudgetAmount(incomeLevel, Budget)
VALUES(3, 500000);

INSERT INTO BudgetAmount(incomeLevel, Budget)
VALUES(4, 750000);

INSERT INTO BudgetAmount(incomeLevel, Budget)
VALUES(5, 1000000);


INSERT INTO PropertyPrice(sqFt, sellPrice)
VALUES(1000, 500000);

INSERT INTO PropertyPrice(sqFt, sellPrice)
VALUES(2000, 750000);

INSERT INTO PropertyPrice(sqFt, sellPrice)
VALUES(2700, 900000);

INSERT INTO PropertyPrice(sqFt, sellPrice)
VALUES(4300, 1400000);

INSERT INTO PropertyPrice(sqFt, sellPrice)
VALUES(5000, 2000000);


INSERT INTO Loan(buyerID, branchID, loanOfficerName, loanOfficerID, loanAmt)
VALUES(1, 001, 'Michael Figgs', 3004050, 100000);

INSERT INTO Loan(buyerID, branchID, loanOfficerName, loanOfficerID, loanAmt)
VALUES(2, 002, 'Jamie Leon', 3052020, 20000);

INSERT INTO Loan(buyerID, branchID, loanOfficerName, loanOfficerID, loanAmt)
VALUES(3, 003, 'Larry OBrian', 31006689, 500000);

INSERT INTO Loan(buyerID, branchID, loanOfficerName, loanOfficerID, loanAmt)
VALUES(4, 004, 'Benson Ash', 5007617, 45000);

INSERT INTO Loan(buyerID, branchID, loanOfficerName, loanOfficerID, loanAmt)
VALUES(5, 005, 'Tim Book', 5059019, 2700);

/*needs seller which needs bank*/
INSERT INTO Hire(sellerID, empNo, Invoice)
VALUES(5, 14, 250) ;

INSERT INTO Hire(sellerID, empNo, Invoice)
VALUES(2, 10, 130) ;

INSERT INTO Hire(sellerID, empNo, Invoice)
VALUES(3, 1, 80) ;

INSERT INTO Hire(sellerID, empNo, Invoice)
VALUES(1, 6, 335) ;

INSERT INTO Hire(sellerID, empNo, Invoice)
VALUES(4, 3, 180) ;

INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (1, '123 Hi St.', 1000, NULL, 450000, 1, NULL, 1);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (2, '111 Bye St.', 1000, 11, 550000, 2, 2, 2);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (3, '12 Ari St.', 2000, 12, 600000, 3, 3, 3);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (4, '13 Har St.', 2000, 13, 575000, 4, 4, 4);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (5, '14 May St.', 2700, 14, 700000, 5, 5, 5);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (6, '909 Seed Dr.', 2000, NULL, 750000, 1, NULL, 3);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (7, '111 Lee St.', 2700, 16, 774000, 2, 3, 4);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (8, '12 Wong St.', 2000, 17, 742000, 3, 4, 5);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (9, '100 Sir St.', 2700, 18, 800000, 4, 5, 1);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (10, '12 Comm Dr.', 2700, 19, 900000, 5, 5, 1);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (11, '145E St.', 4300, 20, 440000, 1, 4, 2);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (12, '188N St.', 4300, 21, 450000, 2, 3, 3);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (13, '999 St.', 4300, 22, 350000, 3, 2, 4);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (14, '110 Moore St.', 4300, 23, 600000, 4, 1, 5);
	
INSERT INTO Property(propertyID, propertyAddress, sqFt, contractNum, buyPrice, realtorID, buyerID, sellerID)
VALUES (15, '115 St. ', 5000, NULL, 499000, 5, NULL, 3);




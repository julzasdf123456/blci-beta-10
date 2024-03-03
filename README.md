
# CRMS, Read & Bill, Cashiering, API Systems for Electric Companies and Cooperatives

### Project Config
- In table CRM_MemberConsumerImages Manually "HexImage - Text" to database;

### Tickets
- In the creation of Tickets, replace the array of METER-RELATED mother tickets to capture all the tickets pertaining to meter complains
    (found in TicketsController.store)
- Disconnection Delinquency Ticket ID - in Tickets, configure the ID of the Disconnection Delinquency inside Tickets.getDisconnectionDelinquencyId() function

### Billing - Rates
- In the "Rates Template", make sure to add the Real Property Tax (RPT) to the overall rate during deployment (optional)
- Make Sure to use the FOR UPLOAD Sheet or FILE
- Also, make sure that the arrangement of Districts on the For Upload Template is not interchanged

### User - Special Authentication
- In UsersController.authenticate(), update the permissions


### KIOSK PRINTING IN CHROME
 --kiosk --kiosk-printing "http://localhost:8000"


### Added New Columns


### Added New Tables
- 

### New Permissions
- turn-on approval
- turn-on assigning
- payment approval

### GENERATE MODELS FROM TABLE
- php artisan infyom:scaffold Post --fromTable --table=posts --connection=server_name
# Kara David's Wish Note

## Project Overview

Kara David's Wish Note is a PHP and MariaDB web application that allows users to enter and submit information about a target. Before submission, the application displays a verification modal so users can review the entered information.

## Technologies Used

- PHP
- MariaDB
- MySQLi
- HTML
- CSS
- JavaScript
- XAMPP

## Database Information

- **Database:** `birthday_wishnote`
- **Table:** `main_corrupt_politicians`

### Table Fields

| Field | Data Type | Requirement |
|---|---|---|
| GuestID | INT | Primary key, auto-increment |
| FirstName | VARCHAR(30) | Required |
| LastName | VARCHAR(30) | Required |
| GovPosition | VARCHAR(20) | Required |
| Location | VARCHAR(40) | Optional |

## Application Features

1. Accepts first name, last name, government position, and location.
2. Enforces required fields and maximum character lengths.
3. Displays a **Verify Target Data** modal before submission.
4. Provides **Cancel** and **Confirm & Send** buttons.
5. Uses a PHP MySQLi prepared statement to insert data into MariaDB.
6. Displays a success message after a successful insertion.

## Test Case

**Test Case ID:** TC-DataEntry-001  
**Test Case Name:** Valid Data Entry

### Test Data

| Field | Value |
|---|---|
| First Name | Cassandra |
| Last Name | Olasiman |
| Government Position | Congressman |
| Location | France |

| Field | Value |
|---|---|
| First Name | Blessy |
| Last Name | Olasiman |
| Government Position | Mayor |
| Location | Sagay |

| Field | Value |
|---|---|
| First Name | Evelyn |
| Last Name | Gonzales |
| Government Position | Senator |
| Location | Hinigaran |

| Field | Value |
|---|---|
| First Name | Sophia |
| Last Name | Japitana |
| Government Position | Vice-Mayor |
| Location | Sagay |

| Field | Value |
|---|---|
| First Name | Kirsten |
| Last Name | Japitana |
| Government Position | Secretary |
| Location | Hinigaran |

### Test Execution Results

| Step | Action | Expected Result | Actual Result |
|---|---|---|---|
| a | Open the web application. | The application loads successfully. | The application opened successfully in the browser. |
| b | Check the form labels and input fields. | All four fields are visible and writable. | The four fields were visible and usable. |
| c | Hover over the Send Wish Note button. | The button changes appearance. | *Pending Execution* |
| d | Enter valid data into all fields. | The form accepts the valid input. | The valid test data was accepted. |
| e | Click Send Wish Note. | The verification modal displays all entered values. | The modal displayed the entered values. |
| f | Click Cancel. | The modal closes without submitting the form. | The form returned without submitting. |
| g | Submit again and click Confirm & Send. | The form is submitted to the server. | The form was submitted successfully. |
| h | Check the application response. | A success message appears after insertion. | The success message appeared. |
| i | Query the database table. | The submitted data is stored in the database. | FirstName, LastName, and GovPosition were saved. Location was NULL instead of France. |

## Defect Observed

The Location field is displayed in the form and verification modal, but the SQL INSERT statement omits the Location column and its corresponding bound parameter. Consequently, the submitted location is not saved to the database.

This intentional defect demonstrates why database testing must verify stored values, not merely rely on a success message.

## Conclusion

The application successfully accepted and submitted the test data. However, the database verification revealed that the Location value was not persisted. The form submission test succeeded, while the Location persistence requirement was not met.

## Reference

The assignment's required reference filename is **"textcase.txt"**. The locally prepared test record is saved as `TestCase.txt`.

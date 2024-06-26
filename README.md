# Web Application | The Motueka Bed & Breakfast
The project involved completing the development of a web application based on a design brief for a fictitious business, The Motueka Bed & Breakfast.

The owners hired a web development company to create a website that would attract more customers and allow them to make online reservations. GUI design aspects of the website would be handled by the web development company. However, they needed a developer, me, to assist their team with developing the code to add functionality to the website. Booking and login pages needed to be developed and rooms and customer pages needed to be updated. Customer-facing pages would attract more customers, whilst administrator pages would allow administrators to manage customers, rooms, and bookings. Authentication, authorisation, session management and logout functionality needed to be implemented.

The project was completed over 3 assessments whilst completing the Web Programming course at Open Polytechnic NZ as part of the Web Development and Design diploma. The web application was built with HTML5, then PHP, JavaScript and a SQL database. JavaScript technologies and libraries implemented include jQuery UI library for picking dates, JavaScript Object Notation (JSON) to search for room availability or customers, and AJAX to help content load asynchronously without waiting for the whole web page to load. The privacy statement was created with the Privacy Statement Generator at https://privacy.org.nz/tools/privacy-statement-generator/ with the type of private identifiable information (PI) that the web application collects from users. A default username and password of 'root' was added to the database to test pages viewed by administrators.

# Security Measures Implemented
User input is validated with HTML5 code to prevent users from entering incorrect information and control the type of data sent to the database. Passwords are encrypted before being sent to the database. Passwords entered during login are verified and compared against encrypted passwords stored in the database to authenticate users. SQL injection attacks are mitigated by using parameterised prepared sql statements. Cross-site Scripting (XSS) attacks are mitigated by filtering and encoding data.

# Improvements / Enhancements
Cross-Site Request Forgery (CSRF) attacks could be mitigated by implementing Cross-Origin Resource Sharing (CORS) headers. For security reasons, the 'root' username and password would need to be updated within the database before going live to allow for a private administrator username and password.

# Screenshots
# Temporary Launch Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/338bd695-7a32-433c-8155-9f0330154561)

# Customer Login Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/d0b78735-c01d-46f5-bac5-93194ea0f0ee)

# Customer Logged in
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/63d3c972-f5ca-41a6-927e-68d46fb06226)

# Customer Logged in as Administrator
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/1554de13-c089-4d17-b736-0f6859024add)

# New Customer Registration Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/71860f97-f3d4-4831-b4cf-b45e7fbd15c5)

# Customer Already Registered Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/53ddf4ea-7631-4978-abf1-36e27ef09bbb)
Administrators can't register here and need to be loaded directly onto the database first for security reasons

# Account Details View Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/5f4b6a3f-7d5f-4fab-a47d-bd789d461030)


# Administrator Pages
# Customer List Search by Lastname | Administrator Landing Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/b1d68ef5-eba7-4170-a39a-823572410857)
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/7e16f877-bf90-41fd-8d11-5d3c679668dd)

# Customer Details View Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/3098e11f-f306-4ee7-b1c9-17bcfaf09153)

# Customer Details Update Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/16d3d906-5570-47ef-ac87-52b247b8c477)

# Customer Details Preview Before Deletion Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/39ac6528-d376-4eb3-ba69-0b6c4db0f5a6)

# Room List Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/cb9424bf-f5d4-4951-a560-fd05435eb9f7)

# Room Details View Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/bf1e6c45-f266-4e4c-b695-282ef7ef498e)

# Room Details Update Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/c106c01a-40f6-443b-aa06-d3fee34170e9)

# Room Details Preview Before Deletion Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/0d30799a-e653-4097-b19d-0224909fd966)

# Current Bookings Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/a65de047-7420-4d03-830f-e95ad4007cd6)

# Booking Details View Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/337d71ae-4815-46d7-b779-d10b6e62ba26)

# Edit a Booking Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/387e75c8-c3d5-40ae-b518-cef459a7b037)

# Booking Preview Before Deletion Page | Administrator Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/bad73656-ed94-4bd4-8911-accec3b7580c)


# Customer Pages
# Not Authorised Page | Authorisation Functionality
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/ddcd042c-3ce0-4b82-b100-588fea7b8a80)

# Room List Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/67e3db11-f1e4-48b4-8077-30cbf87ce4d5)
Edit and delete pages revert to the not authorised page

# Current Bookings Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/f8223874-3313-4045-91ba-ff4bdd2af3d5)
No bookings yet

# Current Bookings Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/7d4e07d4-0ed9-45b1-b177-97b51a56bb81)
Booking made; 
Edit and delete pages revert to the not authorised page

# Make a Booking Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/cdc7ff0f-3c89-45a1-9db9-900c0f197719)

# Search Room Availability on Booking Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/3b9935d2-313f-451a-845c-3aa409585be3)

# Booking Details View Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/8d7ebfe6-496e-4ca2-955b-a25be2d169d2)

# Customer Details Update Page | Customer Page
![image](https://github.com/TanyabYC/motueka.atwebpages.com/assets/129232229/ebbd0c29-2b77-4b51-9b42-6bfbf7cd588d)
Accessible from Account Details View page to allow customers to update their personal information. 

Note: The web application does not allow customers to reset their passwords. Further enhancements could involve updating the Account Details View page instead with customer contact details and e-mail preferences and adding a password reset button for customers to reset their passwords via an e-mail link.

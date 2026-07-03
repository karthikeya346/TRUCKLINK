<div align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&height=200&section=header&text=TruckLink&fontSize=90&animation=fadeIn" />

  <br />
  <p>
    <b>Smart truck rental and booking platform connecting organizations, truck owners, and admins in one seamless system.</b>
  </p>
  
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
  [![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html.com/)
  [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3schools.com/css/)
  [![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)](https://www.javascript.com/)
</div>

---

## 🚀 Features

TruckLink provides three distinct user panels, each tailored for a specific role in the logistics process.

### 🏢 Organization Portal
- **Dashboard:** Overview of total bookings and current status.
- **Book Trucks:** Easily create new logistics bookings specifying source, destination, and payload requirements.
- **Track Status:** Monitor the status of ongoing bookings (Pending, Assigned, Completed).

### 🚛 Truck Owner Portal
- **Dashboard:** Keep track of your registered trucks and their availability.
- **Manage Trucks:** Add new trucks, update details, and view assignment logs.
- **Daily Logs:** Log daily operations, maintenance, and driver details for better transparency.

### 👨‍💼 Admin Portal
- **Centralized Dashboard:** A bird's-eye view of all platform operations.
- **Manage Bookings & Assignments:** Review organization requests and assign available trucks to fulfill bookings efficiently.
- **Location Management:** Add and manage operational routes and locations.
- **Comprehensive Logs:** Access detailed logs of all truck activities and booking history.

---

## 📂 Project Structure

```bash
📦 TruckLink
 ┣ 📂 admin               # Admin dashboard and management scripts
 ┣ 📂 assets              # CSS, Images, and static files
 ┣ 📂 organization        # Organization booking portal
 ┣ 📂 owner               # Truck owner management portal
 ┣ 📜 db_connect.php      # Database connection configuration
 ┣ 📜 index.php           # Landing page
 ┣ 📜 login.php           # Unified authentication page
 ┗ 📜 style.css           # Global stylesheets
```

---

## ⚙️ Installation & Setup

Follow these simple steps to run TruckLink on your local machine:

1. **Clone the repository**
   ```bash
   git clone https://github.com/karthikeya346/TRUCKLINK.git
   cd TRUCKLINK
   ```

2. **Setup the Database**
   - Open your local database manager (e.g., phpMyAdmin).
   - Create a new database named `trucklink_db` (or as configured).
   - Import the provided `.sql` file (if available) to generate the tables.

3. **Configure Database Connection**
   - Open `db_connect.php` (or wherever your config is located).
   - Update the database credentials to match your local setup:
     ```php
     $host = "localhost";
     $user = "root";
     $pass = "";
     $dbname = "trucklink_db";
     ```

4. **Run the Application**
   - Move the project directory to your local server's web root (e.g., `htdocs` for XAMPP or `www` for WAMP).
   - Open your browser and navigate to `http://localhost/TruckLink`.

---

## 🌟 Visuals & Animations

<div align="center">
  <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExc29pdjhkYXhzZ28zZnp4czc0MmoyOTF4ejhyYnQweHJyaXYweHF3cCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/L59aKteZZKls0G7UfJ/giphy.gif" width="600" alt="Truck Animation" style="border-radius:15px;"/>
  <br />
  <i>Connecting logistics seamlessly with real-time tracking and assignments!</i>
</div>

---

<div align="center">
  <h3>Made with ❤️ by <a href="https://github.com/karthikeya346">Karthikeya Saran</a></h3>
  <img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&height=100&section=footer" />
</div>

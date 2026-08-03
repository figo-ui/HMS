# 🏥 HMS — Hospital Management System

A full-featured, modern Hospital Management System built with **Laravel 12** and **Filament 5**, backed by **PostgreSQL**. HMS is designed to streamline day-to-day hospital operations including patient management, OPD/IPD encounters, pharmacy, laboratory, radiology, billing, and staff coordination.

---

## ✨ Features

### 🧑‍⚕️ Patient Management
- Patient registration with MRN (Medical Record Number)
- Patient portal for self-service access
- Patient history tracking
- Insurance management & coverage

### 🏠 OPD & IPD Encounters
- Outpatient Department (OPD) encounter management
- Inpatient Department (IPD) with bed assignment
- Triage scoring and clinical notes
- Appointment scheduling

### 💊 Pharmacy
- Inventory tracking with reorder alerts
- Prescription management & dispensing
- Pharmacy movements log
- Pharmacy sales reporting dashboard

### 🧪 Laboratory & Radiology
- Lab test requests and result management
- Radiology orders with findings & result artifacts
- Service request workflow (request → verify → fulfill)

### 💰 Billing & Payments
- Service request-based billing
- Multi-mode payment (cash, insurance, waived)
- Invoice generation and PDF receipts
- Patient share vs. insurance share breakdown

### 🏢 Staff & Departments
- Doctors, Nurses, and Staff management
- Department organization with foreign key relationships
- Role-based access control via **Filament Shield**

### 📦 Inventory
- Medical supply inventory with stock tracking
- Inventory transaction log
- Batch and expiry date management

### 📊 Reporting
- Hospital dashboard with key metrics
- Pharmacy report dashboard
- Payment and billing reports

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 |
| Admin Panel | Filament 5 |
| Database | PostgreSQL |
| Frontend Assets | Vite + TailwindCSS 4 |
| Auth & Roles | Filament Shield (Spatie Permissions) |
| PHP | 8.2+ |

---

## ⚙️ Requirements

- PHP >= 8.2 with extensions: `pdo_pgsql`, `pgsql`, `intl`, `zip`, `mbstring`, `openssl`, `curl`, `fileinfo`
- PostgreSQL >= 14
- Composer 2.x
- Node.js >= 18 & npm

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/figo-ui/HMS.git
cd HMS
```

### 2. Install PHP dependencies

```bash
php composer.phar install
# or if composer is globally installed:
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and update your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=HMS
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Create PostgreSQL database

```sql
CREATE DATABASE "HMS";
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. (Optional) Seed initial data

```bash
php artisan db:seed
```

---

## 🧑‍💻 Development

Start the development server with hot reloading:

```bash
composer dev
# Runs: php artisan serve + queue:listen + npm run dev concurrently
```

Or run individually:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1
```

---

## 🔐 Roles & Permissions

HMS uses **Filament Shield** for role-based access control. After migrating, create a super-admin:

```bash
php artisan shield:super-admin
```

Sync permissions for all resources:

```bash
php artisan shield:generate --all
```

---

## 🗄️ Database Schema

The system includes 50+ migrations covering:

| Module | Tables |
|--------|--------|
| Core | `users`, `patients`, `doctors`, `nurses`, `staff` |
| Clinical | `appointments`, `o_p_d_s`, `i_p_d_s`, `triages`, `patient_histories` |
| Facilities | `beds`, `departments`, `laboratories`, `radiologies` |
| Pharmacy | `pharmacies`, `pharmacy_movements`, `pharmacy_sales`, `prescriptions` |
| Inventory | `inventories`, `inventory_transactions` |
| Billing | `service_requests`, `services`, `payments` |
| Admin | `settings`, `insurances`, `reports`, `notifications` |
| Auth | `roles`, `permissions`, `model_has_roles` (Spatie) |

---

## 📁 Project Structure

```
app/
├── Filament/
│   ├── Resources/     # Admin panel resources (CRUD)
│   ├── Pages/         # Custom pages (Dashboard, Portal)
│   └── Widgets/       # Dashboard widgets
├── Models/            # Eloquent models
├── Services/          # Business logic services
├── Events/            # Domain events (PaymentRequested, etc.)
└── Policies/          # Authorization policies

database/
├── migrations/        # 51 migration files
└── seeders/

resources/
├── views/             # Blade templates & emails
└── css/               # Tailwind / Filament styles
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -m 'Add my feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

---

## 📄 License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

Built with ❤️ by [figo-ui](https://github.com/figo-ui)

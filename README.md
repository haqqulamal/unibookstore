# 📚 UNIBOOKSTORE - PHP Native Web Project

## 📋 Project Overview

UNIBOOKSTORE is a complete web-based bookstore management system built with **PHP Native** and **MySQL** (XAMPP). It provides features for managing books, publishers, inventory tracking, and procurement planning.

---

## 🎯 Features

✅ **Public Page (index.php)**
- View all available books in a table
- Search books by name or category
- Display publisher information with JOIN query

✅ **Admin Panel (admin.php)**
- Full CRUD operations for books
- Full CRUD operations for publishers
- Modal-based forms for adding/editing data
- Clean table interface with action buttons

✅ **Procurement Report (pengadaan.php)**
- Shows books with critically low stock
- Stock status indicators (Critical, Low, Normal)
- Summary statistics
- Color-coded alerts

✅ **Additional Features**
- Bootstrap 5 CDN styling
- Responsive design
- Clean, well-organized code structure
- Indonesia language interface

---

## 📁 Project Structure

```
/UNIBOOKSTORE
├── config/
│   └── koneksi.php           # Database connection
├── assets/
│   └── style.css             # Custom stylesheet
├── index.php                 # Public page (view all books + search)
├── admin.php                 # Admin panel (CRUD for books & publishers)
├── pengadaan.php             # Procurement report (low stock alert)
├── proses_buku.php           # Book CRUD processing
├── proses_penerbit.php       # Publisher CRUD processing
├── data.sql                  # Database schema & sample data
└── README.md                 # This file
```

---

## 🗄️ Database Schema

### Table: penerbit (Publisher)
```sql
- id_penerbit (VARCHAR, PK)
- nama (VARCHAR)
- alamat (TEXT)
- kota (VARCHAR)
- telepon (VARCHAR)
```

### Table: buku (Book)
```sql
- id_buku (VARCHAR, PK)
- kategori (VARCHAR)
- nama_buku (VARCHAR)
- harga (INT)
- stok (INT)
- id_penerbit (VARCHAR, FK → penerbit)
```

---

## 🚀 Setup Instructions

### Step 1: Prepare XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services

### Step 2: Create Database

**Option A: Using phpMyAdmin**
1. Open http://localhost/phpmyadmin
2. Click "New" to create a new database
3. Database name: `unibookstore`
4. Click "Create"
5. Select the `unibookstore` database
6. Go to "Import" tab
7. Choose the `data.sql` file from this project
8. Click "Import"

**Option B: Using MySQL Command Line**
```bash
mysql -u root -p < data.sql
```
(Just press Enter for password since it's empty by default)

### Step 3: Copy Project to XAMPP
1. Copy the entire `UNIBOOKSTORE` folder to:
   ```
   C:\xampp\htdocs\unibookstore
   ```
   (Or your XAMPP installation directory)

### Step 4: Test the Application
1. Open your browser
2. Navigate to: **http://localhost/unibookstore/**
3. You should see the home page with the list of books

---

## 📖 Usage Guide

### 🏠 Home Page (index.php)
- **View All Books**: Displays all books with publisher information
- **Search**: Enter book name or category to filter results
- **Reset**: Clear search and view all books again
- Shows color-coded stock status (Green: Normal, Yellow: Low, Red: Critical)

### 🔧 Admin Panel (admin.php)

#### Books Tab
- **View Books**: See all books in table format
- **Add Book**: Click "Tambah Buku" button
  - Fill: ID, Category, Name, Price, Stock, Publisher
  - Click "Simpan" to save
- **Edit Book**: Click "Edit" button
  - Modify fields as needed
  - Click "Simpan" to update
- **Delete Book**: Click "Hapus" button
  - Confirm deletion

#### Publishers Tab
- **View Publishers**: See all publishers in table format
- **Add Publisher**: Click "Tambah Penerbit" button
  - Fill: ID, Name, Address, City, Phone
  - Click "Simpan" to save
- **Edit Publisher**: Click "Edit" button
  - Modify fields as needed
  - Click "Simpan" to update
- **Delete Publisher**: Click "Hapus" button
  - Note: Cannot delete if publisher has books
  - Confirm deletion

### 📦 Procurement Page (pengadaan.php)
- Shows top 10 books with lowest stock
- Color-coded status:
  - 🔴 **HABIS/KRITIS**: Stock ≤ 2
  - 🟡 **RENDAH**: Stock 3-5
  - 🟢 **NORMAL**: Stock > 5
- Summary cards showing:
  - Number of critical stock books
  - Number of low stock books
  - Number of normal stock books

---

## 🔧 Technical Details

### Database Connection
- **Host**: localhost
- **User**: root
- **Password**: (empty)
- **Database**: unibookstore
- **Type**: MySQL with mysqli (NOT PDO)

### Security Features
- Input escaping using `mysqli_real_escape_string()`
- Try-catch error handling
- Validation for all form inputs
- Confirmation dialogs for delete operations

### Frontend Features
- Bootstrap 5 CDN (responsive)
- Custom CSS (clean, professional design)
- Modal forms (better UX)
- Color-coded status indicators
- Formatted currency display (Rupiah)
- Responsive tables

---

## 📝 Sample Data Included

**Publishers**: 5 publishers (Gramedia, Erlangga, Mizan, Kompas Gramedia, Gajah Mada UPressional)

**Books**: 12 books across categories:
- Programming (5 books)
- Database (2 books)
- Design, Business, Mobile, Security, Framework (1 each)
- Various stock levels for testing

---

## 🐛 Troubleshooting

### Connection Error
**Error**: "Koneksi Database Gagal"
- **Solution**: 
  - Check if MySQL is running in XAMPP
  - Verify `data.sql` was imported successfully
  - Check credentials in `config/koneksi.php`

### File Not Found
**Error**: "Error 404 - File Not Found"
- **Solution**:
  - Ensure all files are in `C:\xampp\htdocs\unibookstore\`
  - Check folder structure matches the template
  - Restart Apache in XAMPP

### CRUD Operations Not Working
**Error**: Form submissions fail
- **Solution**:
  - Check proses_buku.php and proses_penerbit.php are in correct location
  - Verify form action URLs in admin.php
  - Check MySQL error logs

### Search Not Working
**Error**: Search returns no results
- **Solution**:
  - Ensure books exist in database
  - Check search string is not empty
  - Verify JOIN query in index.php

---

## 📞 File Descriptions

| File | Purpose |
|------|---------|
| `config/koneksi.php` | Database connection configuration |
| `assets/style.css` | Custom styling with Bootstrap integration |
| `index.php` | Public page (view & search books) |
| `admin.php` | Admin dashboard (CRUD management) |
| `pengadaan.php` | Procurement report (low stock alert) |
| `proses_buku.php` | Book CRUD processing (add/edit/delete) |
| `proses_penerbit.php` | Publisher CRUD processing (add/edit/delete) |
| `data.sql` | Database schema and initial data |

---

## 🎓 Code Quality

- ✅ Clean, readable code with comments
- ✅ Proper separation of concerns
- ✅ Consistent naming conventions
- ✅ Error handling and validation
- ✅ Responsive and accessible UI
- ✅ Professional look and feel

---

## 📌 Notes

- No authentication/login required (public access)
- All URLs are relative (works on any path)
- Uses standard PHP functions (mysqli)
- Compatible with XAMPP default settings
- Indonesian language interface

---

## 📄 License

This project is created as an educational example of PHP Native development.

---

**Created**: 2024
**Last Updated**: 2024
**Type**: Educational PHP Native Project


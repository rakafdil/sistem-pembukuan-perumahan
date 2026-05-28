## ERD Diagram
``` mermaid
erDiagram
    %% ==========================================
    %% DEFINISI RELASI DENGAN PENJELASAN DI PANAH
    %% ==========================================
    
    rumah ||--o{ histori_huni : "memiliki histori"
    penghuni ||--o{ histori_huni : "tercatat di"
    
    jenis_iuran ||--o{ tagihan : "menjadi dasar nominal"
    
    penghuni ||--o{ pembayaran : "melakukan pembayaran"
    rumah ||--o{ pembayaran : "dibayar untuk rumah"
    
    pembayaran ||--o{ detail_pembayaran : "dipecah alokasinya ke"
    rumah ||--o{ tagihan : "mempunyai tagihan"
    tagihan ||--o{ detail_pembayaran : "dicicil / dilunasi melalui"
    
    kategori_pengeluaran ||--o{ pengeluaran : "mengkategorikan"

    %% ==========================================
    %% DEFINISI TABEL DAN ATRIBUT
    %% ==========================================

    penghuni {
        bigint id PK
        varchar nama_lengkap
        varchar foto_ktp
        varchar status_penghuni "tetap, kontrak"
        varchar nomor_telepon
        boolean status_menikah
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    rumah {
        bigint id PK
        varchar blok_nomor UK "Unique"
        varchar status_huni "dihuni, kosong"
        timestamp created_at
        timestamp updated_at
    }

    histori_huni {
        bigint id PK
        bigint penghuni_id FK
        bigint rumah_id FK
        date tanggal_mulai
        date tanggal_selesai "Null = Masih menghuni"
        timestamp created_at
        timestamp updated_at
    }

    jenis_iuran {
        bigint id PK
        varchar nama_iuran
        decimal nominal_default
        timestamp created_at
        timestamp updated_at
    }

    tagihan {
        bigint id PK
        bigint rumah_id FK
        bigint jenis_iuran_id FK
        int periode_bulan "1 - 12"
        int periode_tahun "YYYY"
        decimal nominal_tagihan
        varchar status_pembayaran "belum_bayar, sebagian, lunas"
        timestamp created_at
        timestamp updated_at
    }

    pembayaran {
        bigint id PK
        bigint penghuni_id FK "Siapa yang membayar"
        bigint rumah_id FK "Untuk rumah mana"
        date tanggal_bayar
        decimal total_bayar
        varchar metode_pembayaran
        text catatan
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    detail_pembayaran {
        bigint id PK
        bigint pembayaran_id FK
        bigint tagihan_id FK
        decimal nominal_alokasi
        timestamp created_at
    }

    kategori_pengeluaran {
        bigint id PK
        varchar nama_kategori
    }

    pengeluaran {
        bigint id PK
        bigint kategori_id FK
        text deskripsi
        decimal nominal
        date tanggal_pengeluaran
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
```

## Project Structure

```
rt-management-app/          <-- Root folder 
│
├── README.md               <-- Panduan instalasi, screenshot fitur, dan ERD
│
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    <-- Tempat menerima request, memanggil Service/Model, dan membalikkan response JSON
│   │   │   ├── Requests/       <-- Tempat class validasi input dari user
│   │   │   └── Resources/      <-- Tempat standarisasi format response JSON (menyaring data sensitif, memformat tanggal, dll) sebelum dikirim ke frontend
│   │   │
│   │   ├── Models/             <-- Representasi tabel database dan tempat mendefinisikan relasi antar tabel (Eloquent ORM)
│   │   │
│   │   └── Services/           <-- Tempat menaruh business logic kompleks seperti kalkulasi tagihan bulanan atau query grafik laporan keuangan
│   │
│   ├── database/
│   │   ├── migrations/         <-- Skema pembuatan tabel database
│   │   └── seeders/            <-- Script untuk memasukkan data awal/dummy
│   │
│   ├── routes/                 <-- Tempat mendefinisikan URL/Endpoint
│   │
│   └── storage/
│       └── app/public/         <-- Folder untuk menyimpan file fisik hasil upload dari frontend
└── frontend/               <-- Folder project React
    ├── package.json
    ├── public/
    ├── src/
    │   ├── assets/
    │   ├── components/     <-- Reusable components (Button, Modal, Sidebar)
    │   ├── pages/          <-- Halaman utama (Dashboard, Penghuni, Rumah, Keuangan)
    │   ├── services/       <-- Konfigurasi Axios untuk memanggil API backend
    │   ├── utils/          <-- Helper (format uang Rupiah, format tanggal)
    │   ├── App.tsx
    │   └── main.tsx
    └── .env.example        <-- Simpan VITE_API_URL=http://localhost:8000/api
```
## Component Diagram
![component-diagram](./component-diagram.png)


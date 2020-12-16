<?php

use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Feed;
use App\Model\User\User;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('home-url', function ($trail) {
    $trail->push('Home', route('home'));
});

// Pengguna
Breadcrumbs::for('index-user', function ($trail) {
    $trail->push('Pengguna', route('index-user'));
});

Breadcrumbs::for('index-siswa', function ($trail) {
    $trail->push('Siswa', route('index-siswa'));
});

Breadcrumbs::for('create-user', function ($trail) {
    $trail->parent('index-user');
    $trail->push('Tambah Pengguna', route('index-user'));
});

Breadcrumbs::for('create-siswa', function ($trail) {
    $trail->parent('index-siswa');
    $trail->push('Tambah Siswa', route('index-siswa'));
});

// Orangtua
Breadcrumbs::for('index-parent', function ($trail) {
    $trail->push('Orangtua', route('index-parent'));
});

Breadcrumbs::for('create-parent', function ($trail) {
    $trail->parent('index-parent');
    $trail->push('Tambah Orangtua', route('index-parent'));
});

// Kelas
Breadcrumbs::for('student-class', function ($trail) {
    $trail->push('Manajemen Kelas', route('student-class'));
});

Breadcrumbs::for('user-class', function ($trail) {
    $trail->parent('student-class');
    $trail->push('Kelas', route('user-class'));
});

Breadcrumbs::for('create-student-class', function ($trail) {
    $trail->parent('student-class');
    $trail->push('Tambah Kelas', route('student-class'));
});

Breadcrumbs::for('list-student-class', function ($trail, $id_kelas) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('student-class', $id_kelas));
});

Breadcrumbs::for('rekap-tugas', function ($trail, $id_kelas) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push('Rekap Tugas', route('rekap-tugas', $id_kelas));
});

// Feed
Breadcrumbs::for('class-feed', function ($trail, $id_kelas, $id_feed) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $nama_feed = Feed::findOrFail($id_feed);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push($nama_feed->judul, route('class-feed', [$id_kelas, $nama_feed]));
});

Breadcrumbs::for('show-tugas', function ($trail, $id_kelas, $id_feed, $siswa_id) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $nama_feed = Feed::findOrFail($id_feed);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push($nama_feed->judul, route('class-feed', [$id_kelas, $id_feed]));
    $trail->push('Penilaian Tugas', route('show-tugas', [$id_kelas, $id_feed, $siswa_id]));
});

Breadcrumbs::for('class-data', function ($trail, $id_kelas) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push('Data Feed Kelas', route('class-data', $id_kelas));
});

Breadcrumbs::for('siswa-class', function ($trail, $id_kelas) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push('Siswa Kelas', route('siswa-class', $id_kelas));
});

Breadcrumbs::for('tugas-siswa', function ($trail, $id_kelas, $siswa_id) {
    $nama_kelas = StudentClass::findOrFail($id_kelas);
    $nama_siswa = User::findOrFail($siswa_id);
    $trail->parent('student-class');
    $trail->push($nama_kelas->class_name, route('list-student-class', $id_kelas));
    $trail->push('Siswa Kelas', route('siswa-class', $id_kelas));
    $trail->push($nama_siswa->full_name, route('tugas-siswa', [$id_kelas, $siswa_id]));
});

// Siswa
Breadcrumbs::for('siswa', function ($trail) {
    $trail->push('Siswa', route('siswa'));
});

// Role dan Permission
Breadcrumbs::for('role', function ($trail) {
    $trail->push('Role Permission', route('role'));
});

Breadcrumbs::for('create-role', function ($trail) {
    $trail->parent('role');
    $trail->push('Tambah Role', route('role'));
});

Breadcrumbs::for('update-role', function ($trail,$role) {
    $trail->parent('role'); 
    $trail->push('Update ('.Role::findOrFail($role)->name.')', route('role', Role::findOrFail($role)->name));
});

// Profile
Breadcrumbs::for('profile', function ($trail) {
    $trail->push('Profile Pengguna', route('profile'));
});

// Assessment
Breadcrumbs::for('assessment', function ($trail) {
    $trail->push('Penilaian Siswa', route('assessment'));
});

Breadcrumbs::for('create-assessment', function ($trail) {
    $trail->parent('assessment');
    $trail->push('Penilaian Hafalan', route('assessment'));
});

// Action Log
Breadcrumbs::for('action-log', function ($trail) {
    $trail->push('Log Aksi', route('action-log'));
});

// Action Log
Breadcrumbs::for('notification', function ($trail) {
    $trail->push('Notifikasi', route('notification'));
});
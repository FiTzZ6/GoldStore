<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Filter Data Barang</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barang/databarang.css') }}">
</head>
<body>

  <!-- Filter -->
  <div class="filter-container">
    <label>FILTER</label>
    <input type="date">
    <select>
      <option>Semua Cabang</option>
      <option>Cabang 1</option>
      <option>Cabang 2</option>
    </select>
    <select>
      <option>Semua Baki</option>
      <option>Baki 1</option>
      <option>Baki 2</option>
    </select>
    <select>
      <option>Semua Supplier</option>
      <option>Supplier A</option>
      <option>Supplier B</option>
    </select>
    <button>Filter</button>
    <label style="margin-left:auto;">Search:</label>
    <input type="text" placeholder="Search...">
  </div>

  <!-- Tombol kontrol tabel -->
  <div class="table-controls">
    <button>Show 10 rows</button>
    <button>Copy</button>
    <button>CSV</button>
    <button>Excel</button>
    <button>PDF</button>
    <button>Print</button>
  </div>

  <!-- Tabel -->
  <table>
    <thead>
      <tr>
        <th>Nama Barang</th>
        <th>Barcode</th>
        <th>KD Baki</th>
        <th>Berat</th>
        <th>Kadar</th>
        <th>Action</th>
      </tr>
      <tr>
        <th><input type="text" placeholder="Search Nama Barang"></th>
        <th><input type="text" placeholder="Search Barcode"></th>
        <th><input type="text" placeholder="Search KD Baki"></th>
        <th><input type="text" placeholder="Search Berat"></th>
        <th><input type="text" placeholder="Search Kadar"></th>
        <th><input type="text" placeholder="Search Action"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="6" class="no-data">No data available in table</td>
      </tr>
    </tbody>
  </table>

</body>
</html>

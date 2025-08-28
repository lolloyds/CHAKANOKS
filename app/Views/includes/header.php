<?php /* Shared header for dashboard */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CHAKANOKS Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f7f7f7 url("<?= base_url('login.png'); ?>") no-repeat center top;
      background-size: cover;
      min-height: 100vh;
    }
    header {
      background: rgba(0,0,0,0.7);
      color: #fff;
      padding: 12px 16px;
    }
    .layout {
      display: flex;
      min-height: calc(100vh - 52px);
    }
    aside {
      width: 220px;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(2px);
      border-right: 1px solid #ddd;
      padding: 16px;
    }
    aside nav { display: grid; gap: 10px; }
    aside nav div { margin: 0; }
    aside nav a {
      display: block;
      padding: 12px 14px;
      border-radius: 10px;
      background: #fff;
      border: 1px solid #e5e5e5;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      transition: box-shadow 120ms ease, transform 120ms ease, border-color 120ms ease;
    }
    aside nav a:hover {
      background: #fff;
      border-color: #d8d8d8;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      transform: translateY(-1px);
    }
    main {
      flex: 1;
      padding: 24px;
      background: rgba(255,255,255,0.88);
    }
    h2 { margin: 0 0 8px 0; }
    h3 { margin: 0 0 8px 0; }
    .desc { margin: 8px 0 16px; color: #333; }
    .stats { display: grid; grid-template-columns: repeat(4, minmax(140px,1fr)); gap: 12px; }
    .stat { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 12px; text-align: center; }
    .box { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 16px; margin: 16px 0; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 8px 10px; border-bottom: 1px solid #eee; text-align: left; }
    .grid { display: grid; gap: 16px; }
    .grid.grid-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .row { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    label { font-size: 13px; color: #444; }
    input, select { padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; background:#fff; }
    button { padding: 10px 14px; border: none; border-radius: 6px; background: #f39c12; color:#fff; cursor:pointer; }
    button:hover { background: #d35400; }
    .badge { display:inline-block; padding: 4px 8px; border-radius: 999px; font-size: 12px; }
    .badge.pending { background:#fff3cd; color:#856404; border:1px solid #ffeeba; }
    .badge.approved { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
    .badge.rejected { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
    .badge.delivered { background:#d1ecf1; color:#0c5460; border:1px solid #bee5eb; }
    a { color: #d35400; text-decoration: none; }
    a:hover { text-decoration: underline; }
    @media (max-width: 900px) {
      .stats { grid-template-columns: repeat(2, minmax(140px,1fr)); }
      .grid.grid-2 { grid-template-columns: 1fr; }
      .row { grid-template-columns: 1fr; }
      aside { width: 180px; }
    }
  </style>
</head>
<body>
  <header>
    <strong>CHAKANOKS</strong> Management
  </header>
  <div class="layout">


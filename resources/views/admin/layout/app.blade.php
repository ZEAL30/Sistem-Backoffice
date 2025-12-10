<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Gec Groups - Forum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
      * {
        box-sizing: border-box;
      }
      body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        min-height: 100vh;
      }
      .forum-container {
        min-height: 100vh;
        background: #f6f8fb;
      }
      .forum-sidebar {
        width: 260px;
        background: #252C45; /* primary */
        color: #eef2f7;
        overflow-y: auto;
        box-shadow: 2px 6px 20px rgba(0, 0, 0, 0.08);
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
      }
      .forum-main {
        flex: 1;
        margin-left: 260px; /* accommodate fixed sidebar */
        overflow-y: auto;
        padding: 36px;
        background: transparent;
      }
      .forum-header {
        background: #ffffff;
        padding: 20px 26px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 6px 18px rgba(37,44,69,0.06);
      }
      .forum-header h1 {
        font-size: 26px;
        color: #252C45;
        margin-bottom: 6px;
        font-weight: 700;
      }
      .forum-header p {
        color: #64748b;
        font-size: 14px;
      }
    </style>
  </head>
  <body>
    <div class="forum-container">
      {{-- Sidebar --}}
      <div class="forum-sidebar">
        @include('admin.partial.sidebar')
      </div>

      {{-- Main Content --}}
      <div class="forum-main">
        @yield('content')
      </div>
    </div>
  </body>
</html>

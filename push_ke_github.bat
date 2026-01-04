@echo off
echo ========================================================
echo   PUSH PERUBAHAN KE GITHUB (FITUR JALUR KEPINDAHAN)
echo ========================================================
echo.
echo Sedang mengirim perubahan ke branch: feature/veli-jalur-kepindahan...
echo.
echo [PENTING] Jika diminta username & password:
echo 1. Username: Masukkan username GitHub Anda
echo 2. Password: Masukkan Personal Access Token (bukan password login biasa)
echo.
git push origin feature/veli-jalur-kepindahan
echo.
if %errorlevel% equ 0 (
    echo [BERHASIL] Perubahan berhasil dikirim!
    echo Silakan buka repository di browser untuk membuat Pull Request.
) else (
    echo [GAGAL] Terjadi kesalahan saat mengirim perubahan.
    echo Pastikan koneksi internet lancar dan kredensial benar.
)
echo.
pause

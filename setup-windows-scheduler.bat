@echo off
echo Configurando o Agendador de Tarefas do Windows para Laravel...
echo.

REM Obter o caminho atual
set "CURRENT_DIR=%~dp0"
set "CURRENT_DIR=%CURRENT_DIR:~0,-1%"

echo Diretório atual: %CURRENT_DIR%
echo.

REM Criar o comando para o agendador
set "COMMAND=schtasks /create /tn "Laravel News Scheduler" /tr "php artisan schedule:run" /sc daily /st 08:00 /f /ru System /rp "" /s localhost"

echo Executando comando: %COMMAND%
echo.

REM Executar o comando
%COMMAND%

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✓ Agendador de tarefas configurado com sucesso!
    echo.
    echo A tarefa "Laravel News Scheduler" foi criada e executará:
    echo - Diariamente às 8:00 da manhã
    echo - O comando: php artisan schedule:run
    echo - No diretório: %CURRENT_DIR%
    echo.
    echo Para verificar a tarefa criada, execute:
    echo schtasks /query /tn "Laravel News Scheduler"
    echo.
    echo Para remover a tarefa, execute:
    echo schtasks /delete /tn "Laravel News Scheduler" /f
) else (
    echo.
    echo ✗ Erro ao configurar o agendador de tarefas.
    echo.
    echo Alternativa manual:
    echo 1. Abra o "Agendador de Tarefas" do Windows
    echo 2. Clique em "Criar Tarefa Básica"
    echo 3. Nome: Laravel News Scheduler
    echo 4. Descrição: Executa o scheduler do Laravel
    echo 5. Gatilho: Diariamente às 8:00
    echo 6. Ação: Iniciar um programa
    echo 7. Programa: php
    echo 8. Argumentos: artisan schedule:run
    echo 9. Iniciar em: %CURRENT_DIR%
)

echo.
pause

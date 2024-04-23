@echo off
setlocal enabledelayedexpansion

set "source_file=sensors_CN1.csv"

for /l %%i in (1, 1, 239) do (
    set "destination_file=sensors_CN%%i.csv"
    copy "%source_file%" "!destination_file!" > nul
    echo Copied %source_file% to !destination_file!
)
#!/bin/bash

# Function to check the status of the last executed command and exit if it failed
check_status() {
    if [ $? -ne 0 ]; then
        exit 1
    fi
}

# Change to the specified directory
cd ~/openPOWERLINK_V2_CAC/ || exit 1
check_status

# Touch all files found
find . -type f | xargs touch | true

# Change to the build directory
cd apps/OBC_MN/build/linux/ || exit 1
check_status

# Run cmake with specified options
cmake -DCFG_BUILD_KERNEL_STACK="Link to Application" -DCFG_STORE_RESTORE=FALSE ../.. || exit 1
check_status

# Clean the build
make clean || exit 1
check_status

# Build the project
make || exit 1
check_status

# Install the build
make install || exit 1
check_status

# Exit with status 0 if all commands succeeded
exit 0

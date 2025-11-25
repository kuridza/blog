#!/bin/sh
set -e

# Example setup task: print a message
echo "Starting container: running setup tasks..."

# You can add other setup logic here, e.g., waiting for a database,
# substituting environment variables in config files, etc.

echo "Setup complete. Executing command: $@"

# Execute the main command (the CMD from the Dockerfile)
exec "$@"
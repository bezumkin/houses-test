
### First start
```
cp .env.example .env
cp src/.env.example src/.env
./start.sh
./init.sh
```

To stop running containers use `./stop.sh`

### Production build

Production app will be available at http://127.0.0.1:8080 a few minutes after the first launch.

If you want to update this build, just delete `src/public/build` and restart Node container.

### Development

Dev mode application with hot reload available at http://127.0.0.1:3000
import * as path from 'node:path'
import { existsSync } from 'node:fs'

export function findEnv (dir: string, files = ['.env.local', '.env', '.env.example']) {
    for (const name of files) {
        const file = path.resolve(dir, name)
        if (existsSync(file)) {
            return file
        }
    }
    return false
}

export function loadEnv (file: string, config = { path: '' }) {
    config.path = file
    return require('dotenv').config(config).parsed
}

const envFile = findEnv('./')
let env: {[key: string]: any} = {}
if (envFile) {
    env = loadEnv(envFile)
}

export default {
    ssr: false,
    telemetry: false,
    runtimeConfig: {
        public: {API_URL: env.APP_URL + '/api/'}
    },
    css: ['element-plus/dist/index.css'],
}

#! /usr/bin/env node

import fs from "fs";
import path from "path";
import { createRequire } from "module";
import { fileURLToPath } from 'url';
import { Command } from "commander";
import { select } from "@inquirer/prompts";
import figlet from "figlet";
import chalk from "chalk";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const require = createRequire(import.meta.url)
const { name, version } = require("./package.json");

const program = new Command();

const cwd = process.cwd();

(async () => {
    console.log(
        chalk.italic(
            figlet.textSync('CODER CLI')
        )
    );
    console.log(
        chalk.bgYellow(`Version: ${version}\n`)
    );
    program
        .name(name)
        .description("coder 專案小工具")
        .version(version)
        .command("create [projectName]")
        .action(async (projectName) => {
            try {
                if (!projectName) {
                    throw new Error("請輸入專案名稱");
                }
                const project = await select({
                    message: "請選擇要建立的專案範本",
                    choices: [
                        {
                            name: "frontend",
                            value: "vite",
                            description: "vite + vue3 + pinia + liff",
                        },
                        {
                            name: "backend",
                            value: "admin",
                            description: "Web_Manage",
                        },
                    ],
                    default: "vite",
                });
                onCreateProject(projectName, project);
            } catch (error) {
                console.error(error.message);
                process.exit();
            }
        });

    program.parse(process.argv);

    program.on("option:verbose", function () {
        process.env.VERBOSE = this.opts().verbose;
    });
})();

function onCreateProject(projectName, project) {
    const dist = path.join(cwd, projectName, "/");
    if (!fs.existsSync(dist)) {
        fs.mkdirSync(dist, 777);
    }
    const files = fs.readdirSync(dist);
    if (files.length > 0) {
        throw new Error(`The folder ${projectName} is not clean.`);
    }

    const src = path.join(__dirname, "templates", project);
    fs.cpSync(src, dist, { recursive: true });
    console.log(
        chalk.bgGreen(`${project} 專案模板建立完成！\n`)
    );
    console.log(`cd ${projectName}\n`);
    switch (project) {
        case 'vite': {
            console.log(
                chalk.yellow(`**記得先修改 @coder/core 版本號**\n`)
            );
            console.log(`npm install\nnpm run dev`);
            break;
        }
        case 'admin': {
            console.log(`composer install`);
            break;
        }
    }
}

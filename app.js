#! /usr/bin/env node

const { Command } = require("commander");
const fs = require('fs');
const path = require('path');

var { name, version } = require("./package.json");

const program = new Command();

const cwd = process.cwd();

program
    .name(name)
    .description("coder 專案小工具")
    .version(version)
    .command("create [projectName] [project]")
    .action((projectName, project = 'vite') => {
        if (!projectName) {
            console.error('請輸入專案名稱');
            process.exit();
        }
        if (['vite', 'admin'].indexOf(project) === -1) {
            console.error('只能建立 vite 或 admin 專案');
            process.exit();
        }
        try {
            onCreateProject(projectName, project);
        } catch (error) {
            console.error(error.message);
            process.exit();
        }
    });

program.parse(process.argv);

program.on('option:verbose', function () {
    process.env.VERBOSE = this.opts().verbose;
});

function onCreateProject(projectName, project) {
    const dist = path.join(cwd, projectName, '/');
    if (!fs.existsSync(dist)) {
        fs.mkdirSync(dist, 777);
    }
    const files = fs.readdirSync(dist);
    if (files.length > 0) {
        throw new Error(`The folder ${projectName} is not clean.`);
    }

    const src = path.join(__dirname, 'templates', project);
    fs.cpSync(src, dist, { recursive: true });
    console.log(`${project} 專案模板建立完成！`);
    if (project === 'vite') {
        console.log(`\n**記得先修改 @coder/core 版本號**\n\ncd ${projectName}\nnpm install\nnpm run dev`);
    }
}

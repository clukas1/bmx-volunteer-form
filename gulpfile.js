const gulpfile = require('gulp');
const ftp = require('vinyl-ftp');
const fs = require('fs');
const gutil = require('gulp-util');

const globs_code = [
    'includes/**',
    'index.php',
    '*schema.json',
];

const globs_composer = [
    'vendor/**',
];

const globs_data = [
    'data/**',
];


let deployCounter = 1;
let deployConn = {};
let deployTasks = [];
let composerDeployTasks = [];
let dataDeployTasks = [];

function create_deploy_task(taskName, connName, path) {
    gulpfile.task(taskName, function () {
        return gulpfile.src(globs_code, {base: '.', buffer: false})
            .pipe(deployConn[connName].newerOrDifferentSize(path))
            .pipe(deployConn[connName].dest(path))
    });
}

function create_composer_deploy_task(taskName, connName, path) {
    gulpfile.task(taskName, function () {
        return gulpfile.src(globs_composer, {base: '.', buffer: false})
            .pipe(deployConn[connName].newerOrDifferentSize(path))
            .pipe(deployConn[connName].dest(path))
    });
}

function create_data_deploy_task(taskName, connName, path) {
    gulpfile.task(taskName, function () {
        return gulpfile.src(globs_data, {base: '.', buffer: false})
            .pipe(deployConn[connName].newerOrDifferentSize(path))
            .pipe(deployConn[connName].dest(path))
    });
}

try {
    if (fs.existsSync('config.js')) {
        const gulpftp = require('./config.js');
        let deployCredentials = gulpftp.config;
        if (deployCredentials.length) {
            deployCredentials.forEach(function (credentials) {
                const task = 'deploy' + deployCounter;
                const composer_task = 'composer_deploy' + deployCounter;
                const data_task = 'data_deploy' + deployCounter;

                const conn = 'conn' + deployCounter;
                deployTasks.push(task);
                composerDeployTasks.push(composer_task);
                deployConn[conn] = ftp.create({
                    host: credentials.host,
                    user: credentials.user,
                    password: credentials.pass,
                    parallel: 10,
                    log: gutil.log
                });
                create_deploy_task(task, conn, credentials.path);
                create_composer_deploy_task(composer_task, conn, credentials.path);
                create_data_deploy_task(data_task, conn, credentials.path);
                deployCounter++;
            });
            gulpfile.task('deploy', gulpfile.series(deployTasks));
            gulpfile.task('composer_deploy', gulpfile.series(composerDeployTasks));
            gulpfile.task('data_deploy', gulpfile.series(dataDeployTasks));
        }

    } else {
        console.log('Config.js does not exist.');
    }
} catch (err) {
    console.error(err);
}

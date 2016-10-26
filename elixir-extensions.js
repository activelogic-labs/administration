/**
 * Created by daltongibbs on 10/13/16.
 */
var gulp = require('gulp');
var shell = require('gulp-shell');
var Elixir = require('laravel-elixir');

var Task = Elixir.Task;

Elixir.extend('publish', function(command) {

    new Task('publish', function() {
        return gulp.src('').pipe(shell(command));
    })
        .watch('src/public/build/**');

});
// Provide a fileName and get fileExt and mode set based on supported languages

fileExt = fileName.split(".");
fileExt = fileExt[fileExt.length - 1];

var mode =
    fileExt == "js"     ? "text/javascript"
  : fileExt == "json"   ? "text/javascript"
  : fileExt == "coffee" ? "text/x-coffeescript"
  : fileExt == "ts"     ? "application/typescript"
  : fileExt == "rb"     ? "text/x-ruby"
  : fileExt == "py"     ? "text/x-python"
  : fileExt == "mpy"    ? "text/x-python"
  : fileExt == "css"    ? "text/css"
  : fileExt == "less"   ? "text/x-less"
  : fileExt == "md"     ? "text/x-markdown"
  : fileExt == "xml"    ? "application/xml"
  : fileExt == "sql"    ? "text/x-mysql" // also text/x-sql, text/x-mariadb, text/x-cassandra or text/x-plsql
  : fileExt == "erl"    ? "text/x-erlang"
  : fileExt == "yaml"   ? "text/x-yaml"
  : fileExt == "java"   ? "text/x-java"
  : fileExt == "jl"     ? "text/x-julia"
  : fileExt == "c"      ? "text/x-csrc"
  : fileExt == "h"      ? "text/x-csrc"
  : fileExt == "cpp"    ? "text/x-c++src"
  : fileExt == "ino"    ? "text/x-c++src"
  : fileExt == "cs"     ? "text/x-csharp"
  : fileExt == "go"     ? "text/x-go"
  : fileExt == "lua"    ? "text/x-lua"
  : fileExt == "pl"     ? "text/x-perl"
  : fileExt == "scss"   ? "text/x-sass"
                        : "application/x-httpd-php";

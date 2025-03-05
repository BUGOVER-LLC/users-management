require("dotenv").config();
const path = require("path");
const mix = require("laravel-mix");
const environment = process.env.APP_ENV;
const WebpackObfuscation = require("webpack-obfuscator");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
const CompressionPlugin = require("compression-webpack-plugin");
const MomentLocalesPlugin = require("moment-locales-webpack-plugin");
const fs = require("fs");
const { execSync } = require("child_process");
const { VuetifyLoaderPlugin } = require("vuetify-loader");

const bObfuscate = "false" !== process.env.WEBPACK_OBFUSCATE;
const bMinify = bObfuscate || "true" === process.env.WEBPACK_MINIFY;
const bCompress = bObfuscate || "true" === process.env.WEBPACK_COMPRESS;
const bAnalyze = "true" === process.env.WEBPACK_ANALYZE;
const bCleanup = bObfuscate || "true" === process.env.WEBPACK_CLEANUP;

const buildFolder = "builds";
const buildFolderFull = path.resolve(__dirname, "public", buildFolder);

const readdirSync = (dir, list = []) => {
    if (fs.statSync(dir).isDirectory()) {
        fs.readdirSync(dir).map((f) => readdirSync(list[list.push(path.join(dir, f)) - 1], list));
    }
    return list;
};
const cleanMaps = (folder) => {
    console.log("Clean maps: ", folder);
    const dirCont = readdirSync(folder);
    const files = dirCont.filter((elm) => elm.match(/.*\.js\.map$/gi));
    files.forEach((file) => fs.rmSync(file));
};

//List of modules that you want to build, can be overridden at .env file WEBPACK_MODULES variable (comma separated list)
let modules = ["producer"];
if (typeof process.env.WEBPACK_MODULES !== "undefined" && process.env.WEBPACK_MODULES !== "") {
    modules = process.env.WEBPACK_MODULES.toString()
        .split(",")
        .map((s) => s.trim());
}
modules.sort();

console.log("=====================================================");
console.log("    Environment: ", environment);
console.log("    Builds folder: ", buildFolderFull);
console.log("    Modules: ", modules.join(", "));
console.log("    Clean builds: ", bCleanup ? "Yes" : "No");
console.log("    Minify: ", bMinify ? "Yes" : "No");
console.log("    Compress: ", bCompress ? "Yes" : "No");
console.log("    Obfuscate: ", bObfuscate ? "Yes" : "No");
console.log("    Analyze packages: ", bAnalyze ? "Yes" : "No");
console.log("=====================================================");

modules.forEach((moduleName) => {
    // pass asset from each module for compilation
    require(`./resources/asset/${moduleName}/webpack`)(mix, buildFolder);
    mix.copy("./public/fonts", `public/${buildFolder}/${moduleName}/fonts`);
});

let mixOptions = {
    processCssUrls: true,
    purifyCss: false,
    extractStyles: false,
    imgLoaderOptions: {
        enabled: false,
    },
    terser: {
        extractComments: false,
    },
    runtimeChunkPath: `${buildFolder}/vendor`,
};
let webpackConfig = {
    resolve: {
        extensions: [".ts", ".vue", ".js"],
        alias: {
            "@": path.resolve(__dirname, "./resources/asset"),
        },
    },
    output: {
        chunkFilename: `${buildFolder}/chunks/[name].[hash].js`,
    },
    plugins: [new MomentLocalesPlugin()],
    optimization: {
        splitChunks: {
            chunks: "all",
        },
        usedExports: true,
        sideEffects: true,
        mangleWasmImports: true,
        mergeDuplicateChunks: true,
        minimize: bMinify,
        providedExports: true,
        removeEmptyChunks: true,
    },
    performance: {
        maxAssetSize: 10000,
    },
};

if (bMinify) {
    mixOptions.uglify = {
        uglifyOptions: {
            compress: {
                drop_debugger: true,
                sequences: true,
                booleans: true,
                loops: true,
                unused: true,
                warnings: false,
                errors: false,
                drop_console: true,
                unsafe: false,
                comments: false,
            },
        },
    };
    webpackConfig.devtool = "source-map";
}
//obfuscate
if (bObfuscate) {
    webpackConfig.plugins.push(
        new WebpackObfuscation({
            sourceMap: false,
            sourceMapFileName: "[file].map",
            rotateStringArray: true,
            rotateUnicodeArray: true,
        })
    );
    webpackConfig.devtool = "hidden-source-map";
}
if (bCompress) {
    webpackConfig.plugins.push(
        new CompressionPlugin({
            algorithm: "gzip",
            test: /\.js$|\.ts$|\.css$|\.html$|\.jp2$|\.jpg$|\.jpeg$|\.svg$|\.png$|\.webp$|\.mp3$|\.ico$/,
            threshold: 10240,
            minRatio: 1,
            compressionOptions: {
                level: 9,
            },
        }),
        new VuetifyLoaderPlugin({
            match(originalTag, { kebabTag, camelTag, path, component }) {
                if (kebabTag.startsWith("core-")) {
                    return [camelTag, `import ${camelTag} from '@/components/core/${camelTag.substring(4)}.vue'`];
                }
            },
        })
    );
}
if (bAnalyze) {
    webpackConfig.plugins.push(new BundleAnalyzerPlugin());
}
if (bCleanup) {
    mix.webpackConfig({
        output: {
            clean: {
                keep: function (asset) {
                    // remove all files from builds directory. leave hidden files
                    let m = new RegExp(`^\\/?${buildFolder}\\/(?!($|\\.))`);
                    return asset.match(m) === null;
                },
            },
        },
    });
}

if ("local" !== environment) {
    mix.webpackConfig({
        stats: {
            warnings: false,
        },
    });
}

// Global webpack config for all bundles
mix.webpackConfig(webpackConfig)
    .vue({ version: 2 })
    .options(mixOptions)
    .extract({
        to: `${buildFolder}/vendor/vendor.js`,
        libraries: ["vue", "vuex", "vue-router", "vee-validate", "vuetify"],
    })
    .after((stats) => {
        if (bObfuscate) {
            cleanMaps(buildFolderFull);
        }
    })
    .disableSuccessNotifications()
    .disableNotifications()
    .sourceMaps(false, 'source-map')
    .version();

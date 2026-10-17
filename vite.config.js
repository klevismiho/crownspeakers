import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
	build: {
		outDir: 'assets/build',
		emptyOutDir: false,
		manifest: true,

		rollupOptions: {
			input: {
				global: path.resolve(
					__dirname,
					'assets/src/js/main.js'
				),
			},

			output: {
				entryFileNames: 'js/[name].js',
				assetFileNames: (assetInfo) => {
					if (assetInfo.name?.endsWith('.css')) {
						return 'css/[name][extname]';
					}

					return 'assets/[name][extname]';
				},
			},
		},
	},
});
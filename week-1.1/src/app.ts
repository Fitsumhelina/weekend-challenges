import express from 'express';
const app = express();
import fs from 'fs';
import path from 'path';
import { marked } from 'marked';
const userRoute = require('./routes/userRoute');
const taskRoute = require('./routes/taskRoute');

app.use(express.json());
app.use('/', userRoute);
app.use('/tasks', taskRoute);

app.get('/', (req, res) => {
    const possiblePaths = [
    path.resolve(__dirname, '../Index.md'),
    path.resolve(process.cwd(), 'Index.md'),
];

let readmePath: string | null = null;

for (const p of possiblePaths) {
    if (fs.existsSync(p)) {
        readmePath = p;
        break;
    }
}

if (!readmePath) {
    return res.status(500).send('README.md file not found');
}

fs.readFile(readmePath, 'utf8', (err, data) => {
    if (err) {
        return res.status(500).send('Failed to read README.md');
    }

    const html = marked(data);
    res.send(`
        <html>
            <head><title>Taskify README</title></head>
            <body style="font-family: sans-serif; padding: 2rem;">
                ${html}
            </body>
        </html>
    `);
});
});
const PORT = process.env.PORT;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});

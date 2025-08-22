const express = require('express');
const multer = require('multer');
const cors = require('cors');
const path = require('path');
const fs = require('fs');
const app = express();

app.use(cors());

const UPLOADS_DIR = path.join(__dirname, 'uploads');
if (!fs.existsSync(UPLOADS_DIR)) {
    fs.mkdirSync(UPLOADS_DIR);
}

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, UPLOADS_DIR);
    },
    filename: function (req, file, cb) {
        const ext = path.extname(file.originalname);
        const base = path.basename(file.originalname, ext);
        cb(null, `${base}_${Date.now()}${ext}`);
    }
});

const upload = multer({ storage });

app.post('/upload', upload.single('file'), (req, res) => {
    if (!req.file) return res.status(400).json({ error: 'No file uploaded' });
    const publicUrl = `${req.protocol}://${req.get('host')}/uploads/${req.file.filename}`;
    res.json({ url: publicUrl });
});

app.use('/uploads', express.static(UPLOADS_DIR));

app.get('/health', (req, res) => {
    res.json({ status: 'ok' });
});

const PORT = process.env.PORT || 3002;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en puerto ${PORT}`);
});

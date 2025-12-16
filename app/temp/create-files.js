import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import puppeteer from "puppeteer";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function renderIndexHtml(folderPath, page) {
  // Resolve folder and index.html
  const indexPath = path.join(folderPath, "/index.html");
  if (!fs.existsSync(indexPath)) {
    console.warn("⚠️ index.html not found in:", folderPath);
    return;
  }

  const fileUrl = "file://" + path.resolve(indexPath);
  console.log("Rendering:", fileUrl);

  await page.goto(fileUrl, { waitUntil: "networkidle0" });

  const dom = await page.content();

  // output filename = foldername + ".html"
  const folderName = path.basename(folderPath);
  const outputFile = path.join(__dirname, `${folderName}.html`);
  fs.writeFileSync(outputFile, dom);

  console.log("✅ Saved:", outputFile);
}

async function main() {
  const listFile = process.argv[2];

  if (!listFile || !fs.existsSync(listFile)) {
    console.error("Usage: node create-files.js folders.txt");
    process.exit(1);
  }

  const folders = fs
    .readFileSync(listFile, "utf-8")
    .split(/\r?\n/)
    .map(x => x.trim())
    .filter(Boolean);

  const browser = await puppeteer.launch({
    headless: "new",
    args: [
      "--allow-file-access-from-files",
      "--disable-web-security"
    ]
  });

  const page = await browser.newPage();

  for (const folder of folders) {
    await renderIndexHtml(folder, page);
  }

  await browser.close();
  console.log("🎉 Done.");
}

main();

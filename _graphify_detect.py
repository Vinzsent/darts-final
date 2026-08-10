import json, sys
from graphify.detect import detect
from pathlib import Path

result = detect(Path("C:\\xampp\\htdocs\\darts-final"))
output_path = Path("C:\\xampp\\htdocs\\darts-final\\graphify-out")
output_path.mkdir(exist_ok=True)
(output_path / ".graphify_detect.json").write_text(json.dumps(result, ensure_ascii=False), encoding="utf-8")

files = result.get("files", {})
total = result.get("total_files", 0)
words = result.get("total_words", 0)
print(f"Corpus: {total} files · ~{words:,} words")
for k in ["code","document","paper","image","video"]:
    fl = files.get(k, [])
    if fl:
        print(f"  {k}: {len(fl)} files")

(output_path / ".graphify_root").write_text(str(Path("C:\\xampp\\htdocs\\darts-final").resolve()), encoding="utf-8")

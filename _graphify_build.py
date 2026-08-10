import json, sys
from pathlib import Path
from graphify.extract import collect_files, extract
from graphify.build import build_from_json
from graphify.cluster import cluster, score_all
from graphify.analyze import god_nodes, surprising_connections, suggest_questions
from graphify.report import generate
from graphify.export import to_json

def main():
    root = Path("C:\\xampp\\htdocs\\darts-final")
    out_dir = root / "graphify-out"
    out_dir.mkdir(exist_ok=True)

    detect = json.loads((out_dir / ".graphify_detect.json").read_text(encoding="utf-8"))

    code_files = [Path(f) for f in detect.get("files", {}).get("code", [])]

    if code_files:
        result = extract(code_files, cache_root=root)
        (out_dir / ".graphify_ast.json").write_text(json.dumps(result, indent=2, ensure_ascii=False), encoding="utf-8")
        extract_json = result
        print(f"AST: {len(result['nodes'])} nodes, {len(result['edges'])} edges")
    else:
        (out_dir / ".graphify_ast.json").write_text(json.dumps({"nodes":[],"edges":[],"input_tokens":0,"output_tokens":0}, ensure_ascii=False), encoding="utf-8")
        extract_json = {"nodes":[],"edges":[],"input_tokens":0,"output_tokens":0}

    # code-only corpus - empty semantic
    (out_dir / ".graphify_semantic.json").write_text(json.dumps({"nodes":[],"edges":[],"hyperedges":[],"input_tokens":0,"output_tokens":0}), encoding="utf-8")

    merged = {
        "nodes": extract_json.get("nodes", []),
        "edges": extract_json.get("edges", []),
        "hyperedges": [],
        "input_tokens": extract_json.get("input_tokens", 0),
        "output_tokens": extract_json.get("output_tokens", 0),
    }
    (out_dir / ".graphify_extract.json").write_text(json.dumps(merged, indent=2, ensure_ascii=False), encoding="utf-8")
    print(f"Extraction: {len(merged['nodes'])} nodes, {len(merged['edges'])} edges")

    G = build_from_json(merged, root=str(root), directed=False)
    if G.number_of_nodes() == 0:
        print("ERROR: Graph is empty")
        return

    communities = cluster(G)
    cohesion = score_all(G, communities)
    tokens = {"input": merged.get("input_tokens", 0), "output": merged.get("output_tokens", 0)}
    gods = god_nodes(G)
    surprises = surprising_connections(G, communities)
    labels = {cid: f"Community {cid}" for cid in communities}
    questions = suggest_questions(G, communities, labels)

    wrote = to_json(G, communities, str(out_dir / "graph.json"))
    if not wrote:
        print("ERROR: refused to shrink graph.json")

    report = generate(G, communities, cohesion, labels, gods, surprises, detect, tokens, str(root), suggested_questions=questions)
    (out_dir / "GRAPH_REPORT.md").write_text(report, encoding="utf-8")

    export_html(str(out_dir / "graph.json"), str(out_dir / "graph.html"))

    print(f"Graph: {G.number_of_nodes()} nodes, {G.number_of_edges()} edges, {len(communities)} communities")
    print("DONE")

if __name__ == '__main__':
    main()

import json
import os
import hashlib
from datetime import datetime

# Path to the flow JSON
flow_path = r"C:\Users\au610\OneDrive\Skrivebord\Microflows\AI_system\starterfil.json"

# Load the JSON flow
def load_flow(path):
    with open(path, "r", encoding="utf-8") as f:
        return json.load(f)

# Hash an answer record
def hash_answer(answer_record):
    # Combine key fields into a single string for hashing
    hash_input = (
        answer_record.get("node", "") +
        answer_record.get("question", "") +
        answer_record.get("answer", "") +
        answer_record.get("justification", "")
    ).encode("utf-8")
    return hashlib.sha256(hash_input).hexdigest()

# Save a simple report
def save_report(report_data, report_path):
    with open(report_path, "w", encoding="utf-8") as f:
        f.write("# AI System Assessment Report\n\n")
        for entry in report_data:
            f.write(f"- **Node ID**: {entry.get('node', 'N/A')}\n")
            f.write(f"- **Question**: {entry.get('question', 'N/A')}\n")
            f.write(f"- **Answer**: {entry.get('answer', 'N/A')}\n")
            justification = entry.get('justification')
            if justification:
                f.write(f"- **Justification**: {justification}\n")
            f.write(f"- **Timestamp**: {entry.get('timestamp', 'N/A')}\n")
            f.write(f"- **Hash**: {entry.get('hash', 'N/A')}\n\n")

# Run the flow
def run_flow(flow):
    answers = []
    current_node_id = flow["entry"]
    nodes = flow["nodes"]

    while True:
        if current_node_id not in nodes:
            # Check special endings
            if current_node_id == "not_subject_to_the_AI_Act":
                print("\n---")
                print("This system is not subject to the AI Act.")
                break
            elif current_node_id == "end_flow":
                print("\n---")
                print("Thank you for completing the assessment.")
                break
            else:
                print(f"Error: Node '{current_node_id}' not found.")
                break

        current_node = nodes[current_node_id]

        if current_node["type"] == "decision":
            question = current_node.get("question", current_node.get("explanation", "No question found."))
            print("\n" + question)

            options = current_node.get("options", [])
            if not options:
                print("Error: No options available.")
                break

            for idx, option in enumerate(options):
                print(f"{idx + 1}. {option['label']}")

            choice = input("Choose an option number: ")
            try:
                choice_idx = int(choice) - 1
                selected_option = options[choice_idx]
            except (ValueError, IndexError):
                print("Invalid selection. Try again.")
                continue

            # Prepare answer logging
            answer_record = {
                "node": current_node_id,
                "question": question,
                "answer": selected_option["label"],
                "timestamp": datetime.now().isoformat()
            }

            # If 'require_justification' exists in actions, prompt user
            actions = selected_option.get("actions", [])
            if isinstance(actions, list) and "require_justification" in actions:
                justification = input("Please justify your decision: ")
                answer_record["justification"] = justification
            else:
                answer_record["justification"] = ""

            # Add hash of the answer
            answer_record["hash"] = hash_answer(answer_record)

            answers.append(answer_record)

            next_node = selected_option.get("next")
            if not next_node:
                print("No next node specified. Ending flow.")
                break

            current_node_id = next_node

        elif current_node["type"] == "end":
            print("\n" + current_node.get("message", "Assessment complete."))
            break

        else:
            print(f"Unknown node type: {current_node['type']}")
            break

    # When flow ends, generate report
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    report_name = f"assessment_report_{timestamp}.md"
    report_path = os.path.join(os.path.dirname(flow_path), report_name)
    save_report(answers, report_path)
    print(f"\nReport saved at: {report_path}")

# --- Main execution ---
if __name__ == "__main__":
    flow = load_flow(flow_path)
    run_flow(flow)

# AssistentFoundation Plugin

The **AssistentFoundation Plugin** provides the foundational API layer for all MissionBay and BASE3 components related to AI, chatbots, and agent-based systems. It defines a clean set of interfaces that act as contracts between different parts of the framework, ensuring modularity, stability, and consistent integration across plugins.

---

## Purpose

In complex agent-driven systems, a clear separation between **API definitions** and their **implementations** is essential.
The AssistentFoundation Plugin serves exactly this purpose:

* Central place for all **interfaces** related to AI assistants, chatbots, and MissionBay workflows
* Guarantees consistent **contracts** for developers implementing or extending functionality
* Improves **maintainability** by avoiding circular dependencies between plugins
* Provides a **stable surface** that other plugins or external systems can rely on

---

## Scope

This plugin is focused solely on **interfaces**.
It does not contain implementations, storage logic, or UI elements. Instead, it defines the contracts for the following areas:

* **Agents** – lifecycle, execution, and orchestration of assistant nodes
* **Contexts** – passing state and variables across nodes and flows
* **Memories** – storing and retrieving conversational or session history
* **Nodes** – defining input/output structure and execution contracts
* **Resources** – external services or tools connected to the agent system
* **Config & Value Resolution** – consistent way to inject runtime configuration

---

## Integration

The AssistentFoundation Plugin is designed to be imported by other MissionBay/BASE3 plugins, such as:

* **Chatbot** (Services)
* **MissionBay** (execution engine and node definitions)

By depending only on the interfaces in AssistentFoundation, these plugins remain decoupled from specific implementations.

---

## Benefits

* **Clear contracts**: Every service or node knows exactly what to expect
* **Extensibility**: New nodes and resources can be added without breaking existing code
* **Reusability**: Interfaces can be shared across multiple plugins or projects
* **Future-proofing**: Stable API surface makes upgrades and refactoring easier

---

## Example Structure

```
AssistentFoundation/
 └─ src/
     └─ Api/
         ├─ IAgent.php
         ├─ IAgentContext.php
         ├─ IAgentMemory.php
         ├─ IAgentNode.php
         ├─ IAgentResource.php
         ├─ IAgentConfigValueResolver.php
         └─ ...
```

---

## License

This plugin follows the licensing and contribution model of the BASE3 Framework.
Please check the main repository for details.


If you are unsure whether your system is designed to operate according to one or more implicit or explicit objectives, you have two choices:

1. **Assume it _does_ operate according to one or more implicit or explicit objectives (recommended).**

2. **Assume it _does not_ operate according to one or more implicit or explicit objectives.**

We recommend you assume it _does_ (option 1).

**Why?**  
_Why choose option 1?_
If you assume your system is designed to operate according to one or more implicit or explicit objectives, it might be covered by the AI Act (if the other conditions for having an AI system are also fulfilled), but if it is not prohibited or high-risk, the rules you need to follow are usually simple. Therefore, this approach makes sure you are on the safe side.

All functional AI systems are goal-directed. They do not act at random. This means they are, by design, oriented toward achieving certain outcomes. These objectives may be:

- Explicit: clearly defined by the developer (e.g., optimizing a cost function, maximizing a probability, or achieving a cumulative reward).

- Implicit: not directly stated, but inferable from how the system behaves, from its training data, or from analyzing how it interacts with its environment.

Think of explicit objectives as the goals you say out loud, and implicit objectives as your unspoken (and perhaps unconscious) habits that guide your life.

As an example, imagine an AI system developed by a bank to assess credit applications. The developers define the system’s explicit objective as predicting the likelihood that an applicant will repay a loan, based on financial indicators such as income and existing debt. This goal is encoded directly into the model.

However, during training, the system is exposed to historical data that reflects real-world biases. For example, in the past, the bank may have systematically denied loans to certain groups—such as racial minorities, immigrants, or single mothers—due to biased human decision-making or discriminatory institutional policies. As a result, the AI model may implicitly learn that these groups correlate with higher default risk, not because they actually are riskier, but because the historical data says they were rejected more often.

Over time, the AI starts to favour applicants who resemble those who were previously approved. It begins optimizing for traits that were not explicitly part of its objective, such as zip code, school attended, or employment at certain companies—traits that may act as proxies for race, gender, or socio-economic background. The system is still minimizing loan default risk, but the path it takes toward that goal is now shaped by unjustified and potentially unlawful assumptions.This is not a flaw in the code, it is a consequence of the system's implicit objectives.

Without understanding what the system is explicitly and implicitly optimising for, you cannot assess whether it complies with relevant laws, such as non-discrimination laws, explain its behaviour to users or regulators, or correct unintended harms. Therefore, assuming your system operates according to one or more objectives is the safer and more responsible position. By understanding your system’s goals, you can help prevent legal violations and reputational damage, and build a foundation for responsible AI governance, which is increasingly expected by most stakeholders.

_Why choose option 2?_
If your system is purely theoretical or operates at random, it does not operate according to one or more implicit or explicit objectives. In that case, you can choose option 2. However, be cautious - most functional systems are goal-directed in practice. Even if a system’s objectives are not explicitly defined, patterns in the underlying data, model architecture, or feedback mechanisms can lead it to optimise for certain outcomes. If you overlook these objectives, you may miss critical risks that arise from how the system actually behaves in the real world, like bias or unfair treatment.

**When in doubt, assume your system operate according to one or more implicit or explicit objectives.** Better safe than sorry.

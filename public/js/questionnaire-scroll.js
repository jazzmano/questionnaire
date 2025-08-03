/**
 * Questionnaire Auto-Scroll Handler
 *
 * Automatically scrolls to the top of the page when users navigate
 * between questions in the questionnaire flow.
 *
 * Uses MutationObserver to detect DOM changes when new questions load
 * and triggers smooth scrolling to ensure users see the new question.
 */

class QuestionnaireAutoScroll {
    constructor() {
        this.initialLoadComplete = false;
        this.observer = null;
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        // Allow time for Livewire to initialize before starting observation
        setTimeout(() => {
            this.initialLoadComplete = true;
        }, 1000);

        // Create observer to watch for content changes
        this.observer = new MutationObserver((mutations) => this.handleMutations(mutations));

        // Start observing the main questionnaire container
        const container = document.querySelector('.p-6.flex.flex-col');
        if (container) {
            this.observer.observe(container, {
                childList: true,
                subtree: true
            });
        }
    }

    handleMutations(mutations) {
        // Skip mutations during initial page load
        if (!this.initialLoadComplete) return;

        // Check if any mutations contain question content
        const shouldScroll = mutations.some(mutation => {
            if (mutation.type !== 'childList' || mutation.addedNodes.length === 0) {
                return false;
            }

            return Array.from(mutation.addedNodes).some(node => {
                if (node.nodeType !== Node.ELEMENT_NODE || !node.querySelector) {
                    return false;
                }

                // Look for question-related content changes
                return node.querySelector('flux\\:heading') ||
                    node.querySelector('[class*="heading"]') ||
                    node.textContent.includes('Choose your answer') ||
                    node.textContent.includes('Your Response');
            });
        });

        if (shouldScroll) {
            this.scrollToTop();
        }
    }

    scrollToTop() {
        console.log('New question detected - scrolling to top');

        // Small delay to ensure DOM is fully updated
        setTimeout(() => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 150);
    }

    // Method to manually trigger scroll (if needed)
    triggerScroll() {
        this.scrollToTop();
    }

    // Cleanup method
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
}

// Initialize when script loads
new QuestionnaireAutoScroll();

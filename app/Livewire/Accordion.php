<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class Accordion extends Component
{
    public $content = '';
    public $blocks = [];
    public $openSections = [];
    public $markdownFile = '';

    public function mount($markdownFile = null)
    {
        $this->markdownFile = $markdownFile ?? '';
        $this->loadBlocks();
    }

    public function updatedMarkdownFile()
    {
        $this->loadBlocks();
        $this->openSections = []; // Reset open sections when content changes
    }

    private function loadBlocks()
    {
        if ($this->markdownFile) {
            $this->blocks = $this->loadMarkdownFile($this->markdownFile);
        } else {
            $this->blocks = [];
        }
    }

    public function toggleSection($index)
    {
        if (in_array($index, $this->openSections)) {
            $this->openSections = array_filter($this->openSections, function ($section) use ($index) {
                return $section !== $index;
            });
        } else {
            $this->openSections[] = $index;
        }
    }

    #[On('refreshAccordion')]
    public function refreshAccordion($markdownFile)
    {
        $this->markdownFile = $markdownFile;
        $this->loadBlocks();
        $this->openSections = []; // Reset open sections when content changes
    }

    public function loadMarkdownFile($markdownFile)
    {
        if (empty($markdownFile)) {
            return [];
        }

        $filePath = resource_path("decisiontree/{$markdownFile}");

        if (!file_exists($filePath) || !is_file($filePath)) {
            return [];
        }

        $markdown = file_get_contents($filePath);
        $lines = explode("\n", $markdown);

        $blocks = [];
        $insideAccordion = false;
        $currentAccordion = null;
        $currentContent = '';

        foreach ($lines as $line) {
            // Start of accordion block
            if (preg_match('/^:::accordion (.+)$/i', $line, $matches)) {
                // Save any preceding normal content
                if (!empty(trim($currentContent))) {
                    $blocks[] = [
                        'type' => 'content',
                        'markdown' => trim($currentContent),
                    ];
                    $currentContent = '';
                }

                $insideAccordion = true;
                $currentAccordion = [
                    'type' => 'accordion',
                    'heading' => $matches[1],
                    'body' => '',
                ];
            }
            // End of accordion block
            elseif (trim($line) === ':::') {
                if ($insideAccordion && $currentAccordion) {
                    $blocks[] = $currentAccordion;
                    $currentAccordion = null;
                    $insideAccordion = false;
                }
            }
            // Inside accordion
            elseif ($insideAccordion && $currentAccordion !== null) {
                $currentAccordion['body'] .= $line . "\n";
            }
            // Outside accordion
            else {
                $currentContent .= $line . "\n";
            }
        }

        // Capture any remaining content after the loop
        if (!empty(trim($currentContent))) {
            $blocks[] = [
                'type' => 'content',
                'markdown' => trim($currentContent),
            ];
        }

        return $blocks;
    }

    public function render()
    {
        return view('livewire.accordion');
    }
}

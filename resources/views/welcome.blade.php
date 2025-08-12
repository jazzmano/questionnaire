<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <title>Dicalist - AI Act Compliance Assessment</title>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <div
        class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900">

        <!-- Navigation -->
        <header class="relative z-10">
            <nav class="container mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl font-bold text-white">Dicalist</div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="relative">
            <div class="container mx-auto px-6 pt-20 pb-32">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Main Heading -->
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
                        AI Act
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600">
                            Compliance
                        </span>
                        <br class="hidden md:block" />
                        Made Simple
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl md:text-2xl text-slate-300 mb-12 max-w-2xl mx-auto leading-relaxed">
                        Navigate the European AI Act with confidence. Our intelligent questionnaire helps you
                        assess compliance requirements and generate comprehensive reports.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                        <a href="{{ url('/questionnaire') }}"
                            class="px-8 py-4 bg-yellow-500 hover:bg-yellow-600 text-white text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                            Start Assessment
                        </a>
                        <a href="#features"
                            class="px-8 py-4 border-2 border-slate-400 hover:border-yellow-400 text-slate-300 hover:text-white text-lg font-semibold rounded-xl transition-all duration-300">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="relative bg-white/5 backdrop-blur-sm border-t border-yellow-400/30">
                <div class="container mx-auto px-6 py-20">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-16">
                            Why Choose Dicalist?
                        </h2>

                        <div class="grid md:grid-cols-3 gap-8">
                            <!-- Feature 1 -->
                            <div
                                class="bg-white/10 backdrop-blur-sm rounded-xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Smart Assessment</h3>
                                <p class="text-slate-300 leading-relaxed">
                                    Our intelligent questionnaire adapts to your responses, ensuring you only answer
                                    relevant questions for your specific AI system.
                                </p>
                            </div>

                            <!-- Feature 2 -->
                            <div
                                class="bg-white/10 backdrop-blur-sm rounded-xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Detailed Reports</h3>
                                <p class="text-slate-300 leading-relaxed">
                                    Generate comprehensive compliance reports with actionable recommendations and clear
                                    next steps for your AI system.
                                </p>
                            </div>

                            <!-- Feature 3 -->
                            <div
                                class="bg-white/10 backdrop-blur-sm rounded-xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Expert Knowledge</h3>
                                <p class="text-slate-300 leading-relaxed">
                                    Built with legal expertise and continuously updated to reflect the latest AI Act
                                    requirements and interpretations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Section -->
            <div id="how-it-works" class="relative">
                <div class="container mx-auto px-6 py-20">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-16">
                            How It Works
                        </h2>

                        <div class="grid md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                    1
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Answer Questions</h3>
                                <p class="text-slate-300">
                                    Complete our guided assessment about your AI system's characteristics and use cases.
                                </p>
                            </div>

                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                    2
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Get Analysis</h3>
                                <p class="text-slate-300">
                                    Our system analyzes your responses against AI Act requirements and classifications.
                                </p>
                            </div>

                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                    3
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-4">Download Report</h3>
                                <p class="text-slate-300">
                                    Receive a comprehensive compliance report with recommendations and next steps.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative border-t border-white/10 bg-black/20">
            <div class="container mx-auto px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="text-lg font-semibold text-white">Dicalist</div>
                    </div>
                    <p class="text-slate-400 text-sm">
                        © {{ date('Y') }} Dicalist. Professional AI Act compliance assessment.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>

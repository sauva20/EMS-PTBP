<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('master.campuses.index') }}" class="p-2 rounded-full bg-white shadow-sm border border-slate-200 text-slate-500 hover:text-[#009B77] hover:border-[#009B77]/30 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Create New Campus</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-2 w-full bg-gradient-to-r from-[#009B77] to-emerald-500"></div>
                
                <form action="{{ route('master.campuses.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Campus Name <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                    class="w-full rounded-xl border-slate-200 focus:border-[#009B77] focus:ring focus:ring-[#009B77]/20 transition-all shadow-sm bg-slate-50 focus:bg-white" 
                                    placeholder="e.g. Main Campus">
                                @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="block text-sm font-semibold text-slate-700 mb-1">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}"
                                    class="w-full rounded-xl border-slate-200 focus:border-[#009B77] focus:ring focus:ring-[#009B77]/20 transition-all shadow-sm bg-slate-50 focus:bg-white" 
                                    placeholder="e.g. Jakarta, Indonesia">
                                @error('location') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full rounded-xl border-slate-200 focus:border-[#009B77] focus:ring focus:ring-[#009B77]/20 transition-all shadow-sm bg-slate-50 focus:bg-white resize-none" 
                                    placeholder="Optional details about this campus...">{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-slate-300 text-[#009B77] focus:ring-[#009B77] transition-all" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div>
                                        <div class="font-semibold text-slate-800">Active Campus</div>
                                        <div class="text-sm text-slate-500">Uncheck to hide this campus from selection menus.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('master.campuses.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#009B77] to-emerald-600 rounded-xl shadow-md shadow-[#009B77]/20 hover:shadow-lg hover:shadow-[#009B77]/40 transform hover:-translate-y-0.5 transition-all">
                                Save Campus
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div>
<label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
Amount *
</label>
<input type="number"
   name="amount"
   id="amount"
   value="{{ old('amount', $expense->amount) }}"
   required
   step="0.01"
   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
@error('amount')
<span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div>
<label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
Date *
</label>
<input type="date"
   name="date"
   id="date"
   value="{{ old('date', $expense->date) }}"
   required
   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
@error('date')
<span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div>
<label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
Description
</label>
<textarea name="description"
      id="description"
      rows="3"
      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">{{ old('description', $expense->description) }}</textarea>
@error('description')
<span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div class="flex justify-end">
<button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-300">
<i class="fas fa-save mr-2"></i>Update Expense
</button>
</div>
</form>
</div>
</div>
</div>
</div>
</x-app-layout>
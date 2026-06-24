import pandas as pd
import matplotlib.pyplot as plt

# Load the CSV file
df = pd.read_csv(r'C:\Users\emmam\Downloads\cuisine.csv')

print("=" * 60)
print("QUESTION 3.1: Summary Statistics")
print("=" * 60)
print(df.describe())
print("\nObservations about the data:")
print("1. The dataset contains numerical columns with varying ranges and distributions.")
print("2. The summary statistics show mean, standard deviation, min, max, and quartiles for each numeric column.")

print("\n" + "=" * 60)
print("QUESTION 3.2: Create Age Column")
print("=" * 60)
df['Age'] = 2025 - df['YOB']
print("Age column created successfully.")
print("\nFirst few rows with Age:")
print(df[['YOB', 'Age']].head())

print("\n" + "=" * 60)
print("QUESTION 3.3: Side-by-Side Histograms")
print("=" * 60)

# Create side-by-side histograms
fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(14, 5))

# Histogram for Age with 15 bins
ax1.hist(df['Age'], bins=15, edgecolor='black', color='skyblue')
ax1.set_xlabel('Age')
ax1.set_ylabel('Frequency')
ax1.set_title('Distribution of Age (15 bins)')
ax1.grid(axis='y', alpha=0.3)

# Histogram for Overall Rating with 10 bins
ax2.hist(df['Overall Rating'], bins=10, edgecolor='black', color='lightcoral')
ax2.set_xlabel('Overall Rating')
ax2.set_ylabel('Frequency')
ax2.set_title('Distribution of Overall Rating (10 bins)')
ax2.grid(axis='y', alpha=0.3)

plt.tight_layout()
plt.savefig('age_rating_histograms.png', dpi=100, bbox_inches='tight')
print("Histograms saved as 'age_rating_histograms.png'")
plt.show()

# Calculate most common and least common Ages and Ratings
age_counts = df['Age'].value_counts().sort_values(ascending=False)
rating_counts = df['Overall Rating'].value_counts().sort_values(ascending=False)

print("\nAge Distribution:")
print(f"Most common Age: {age_counts.index[0]} (count: {age_counts.iloc[0]})")
print(f"Least common Age: {age_counts.index[-1]} (count: {age_counts.iloc[-1]})")

print("\nOverall Rating Distribution:")
print(f"Most common Rating: {rating_counts.index[0]} (count: {rating_counts.iloc[0]})")
print(f"Least common Rating: {rating_counts.index[-1]} (count: {rating_counts.iloc[-1]})")

print("\n" + "=" * 60)
print("QUESTION 3.4: Average Food Rating per Cuisine")
print("=" * 60)

# Calculate average food rating per cuisine
avg_food_rating = df.groupby('Cuisine')['Food Rating'].mean().sort_values(ascending=False)

print("\nAverage Food Rating per Cuisine:")
print(avg_food_rating)

# Get top 3 cuisines
top_3_cuisines = avg_food_rating.head(3)
print("\nTop 3 Cuisines with Highest Average Food Rating:")
print(top_3_cuisines)

# Create bar chart for top 3 cuisines
plt.figure(figsize=(10, 6))
top_3_cuisines.plot(kind='bar', color='steelblue', edgecolor='black')
plt.xlabel('Cuisine')
plt.ylabel('Average Food Rating')
plt.title('Top 3 Cuisines by Average Food Rating')
plt.xticks(rotation=45, ha='right')
plt.grid(axis='y', alpha=0.3)
plt.tight_layout()
plt.savefig('top_3_cuisines.png', dpi=100, bbox_inches='tight')
print("\nBar chart saved as 'top_3_cuisines.png'")
plt.show()

print("\n" + "=" * 60)
print("ANALYSIS COMPLETE")
print("=" * 60)

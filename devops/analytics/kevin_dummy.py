import pandas as pd

df = pd.read_hdf("filtered_data.hdf", "df1")
df = df[(df.type.isin(['item.thumbnail', 'page_view']))].sort_values('created_at')
df = df.groupby("session").agg(lambda x: "-".join(x))
for tuple in df.itertuples():
    print(tuple)
    break
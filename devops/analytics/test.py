import pandas as pd

df = pd.read_hdf("filtered_data.hdf", "df1")


def page_entity(page):
    p = False
    if page == "/":
        p = 'home'
    if "/item/" in page:
        p = 'item'
    if p == False:
        p = 'unknown'


df_pv['page_entity'] = df_pv['data'].apply(lambda x: page_entity(x))
df_pv = df[(df.type == 'page_view') and (df.source == 1)]
len(df_pv.index)

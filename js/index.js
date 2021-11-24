async function handlerHideProduct(id) {
  const url = '/hide-product.php'
  const headers = {
    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
  }

  try {
    const response = await fetch(url, {
      method: 'POST',
      headers,
      body: `id=${id}`,
    })

    if (response.ok) {
      window.location = '/?page=1'
    }
  } catch (e) {
    console.error(e)
  }
}

function handlerButtonPlus(page, limit) {
  window.location = `/?page=${page}&limit=${limit + 1}`
}

function handlerButtonMinus(page, limit) {
  if (limit > 1) limit--
  window.location = `/?page=${page}&limit=${limit}`
}
